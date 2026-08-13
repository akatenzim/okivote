<?php

namespace App\Services;

use App\Contracts\PaymentGatewayInterface;
use App\Models\Candidate;
use App\Models\Event;
use App\Models\Payment;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TransactionService
{
    public function __construct(
        protected PaymentGatewayInterface $paymentGateway
    ) {}

    public function createTransaction(array $data): Transaction
    {
        return DB::transaction(function () use ($data) {
            // 1. Read Candidate & Event langsung dari Database
            $candidate = Candidate::with('event')->findOrFail($data['candidate_id']);
            $event = $candidate->event;

            // 2. Business Rules Validation (Voting Period & Active State)
            if ($event->status === 'SUSPENDED') {
                throw new \Exception('Event sedang ditangguhkan.');
            }

            $now = now();
            if ($event->voting_start_at && $now->lt($event->voting_start_at)) {
                throw new \Exception('Periode voting belum dimulai.');
            }

            if ($event->voting_end_at && $now->gt($event->voting_end_at)) {
                throw new \Exception('Periode voting telah berakhir.');
            }

            if (!$candidate->is_active) {
                throw new \Exception('Kandidat tidak aktif.');
            }

            // 3. Server-side Financial Calculations
            $voteQuantity = (int) $data['vote_quantity'];
            $votePriceSnapshot = (int) $event->vote_price; // Snapshot harga per event saat ini
            $subtotal = $voteQuantity * $votePriceSnapshot;

            $serviceFee = (int) config('okivote.default_service_fee', 0); // Flat fee atau 0
            $paymentFee = 0;
            $grandTotal = $subtotal + $serviceFee + $paymentFee;

            // 4. Generate Unique Invoice Number
            $invoiceNumber = 'OKV-' . date('Ymd') . '-' . strtoupper(Str::random(6));

            // 5. Save Transaction Record
            $transaction = Transaction::create([
                'invoice_number' => $invoiceNumber,
                'event_id' => $event->id,
                'candidate_id' => $candidate->id,
                'voter_name' => $data['voter_name'],
                'voter_phone' => $data['voter_phone'],
                'support_message' => $data['support_message'] ?? null,
                'is_anonymous' => $data['is_anonymous'] ?? false,
                'vote_quantity' => $voteQuantity,
                'vote_price' => $votePriceSnapshot, // Snapshot Price Saved!
                'subtotal' => $subtotal,
                'service_fee' => $serviceFee,
                'payment_fee' => $paymentFee,
                'grand_total' => $grandTotal,
                'payment_method' => $data['payment_method'] ?? 'QRIS',
                'status' => 'PENDING',
                'expires_at' => now()->addMinutes(config('okivote.transaction_expiry_minutes', 15)),
            ]);

            // 6. Request Charge to Gateway & Create Payment Attempt Record
            $gatewayResponse = $this->paymentGateway->createCharge($transaction, [
                'payment_method' => $data['payment_method'] ?? 'QRIS',
            ]);

            Payment::create([
                'transaction_id' => $transaction->id,
                'gateway' => $gatewayResponse['gateway'],
                'gateway_reference' => $gatewayResponse['gateway_reference'],
                'payment_method' => $gatewayResponse['payment_method'],
                'amount' => $grandTotal,
                'status' => 'PENDING',
                'raw_response' => $gatewayResponse['raw_response'],
            ]);

            return $transaction;
        });
    }
}