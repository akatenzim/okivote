<?php

namespace Tests\Feature;

use App\Models\Candidate;
use App\Models\Event;
use App\Models\Transaction;
use App\Models\VoteLedger;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OkiVoteCoreEngineTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test 1: Price Snapshot Isolation.
     */
    public function test_price_change_does_not_affect_existing_transactions(): void
    {
        $event = Event::factory()->create([
            'vote_price' => 2000,
            'status' => 'ONGOING',
            'published_at' => now(),
        ]);

        $candidate = Candidate::factory()->create([
            'event_id' => $event->id,
            'is_active' => true,
        ]);

        $transaction = Transaction::create([
            'invoice_number' => 'OKV-TEST-001',
            'event_id' => $event->id,
            'candidate_id' => $candidate->id,
            'voter_name' => 'Voter Test',
            'voter_phone' => '081234567890',
            'vote_quantity' => 10,
            'vote_price' => 2000, // Snapshot harga saat transaksi
            'subtotal' => 20000,
            'service_fee' => 0,
            'payment_fee' => 0,
            'grand_total' => 20000,
            'status' => 'PENDING',
        ]);

        // Event diubah harganya oleh Admin di tengah jalan
        $event->update(['vote_price' => 5000]);

        // Transaksi lama HARUS tetap 2000 (tidak ikut berubah)
        $this->assertEquals(2000, $transaction->fresh()->vote_price);
        $this->assertEquals(20000, $transaction->fresh()->grand_total);
    }

    /**
     * Test 2: Idempotent Webhook & Race Condition Prevention.
     */
    public function test_duplicate_webhook_does_not_credit_double_votes(): void
    {
        $event = Event::factory()->create([
            'vote_price' => 1000,
            'status' => 'ONGOING',
        ]);

        $candidate = Candidate::factory()->create([
            'event_id' => $event->id,
            'is_active' => true,
        ]);

        $transaction = Transaction::create([
            'invoice_number' => 'OKV-TEST-002',
            'event_id' => $event->id,
            'candidate_id' => $candidate->id,
            'voter_name' => 'Voter Test',
            'voter_phone' => '081234567890',
            'vote_quantity' => 50,
            'vote_price' => 1000,
            'subtotal' => 50000,
            'service_fee' => 0,
            'payment_fee' => 0,
            'grand_total' => 50000,
            'status' => 'PENDING',
        ]);

        // Webhook Simulasi 1
        $this->postJson(route('webhook.dummy'), [
            'invoice_number' => $transaction->invoice_number,
            'amount' => 50000,
        ]);

        // Webhook Simulasi 2 (Duplikat)
        $this->postJson(route('webhook.dummy'), [
            'invoice_number' => $transaction->invoice_number,
            'amount' => 50000,
        ]);

        // Assert: Hanya ada 1 record VoteLedger untuk transaksi ini
        $this->assertEquals(1, VoteLedger::where('transaction_id', $transaction->id)->count());

        // Assert: Total vote sah kandidat tetap 50 (bukan 100)
        $this->assertEquals(50, VoteLedger::where('candidate_id', $candidate->id)->sum('vote_amount'));
    }
}