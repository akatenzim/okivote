<?php

namespace App\Services;

use App\Models\Candidate;
use App\Models\Transaction;
use App\Models\VoteLedger;
use Illuminate\Support\Facades\DB;

class VoteService
{
    /**
     * Menerbitkan vote sah dari transaksi yang sudah PAID (Idempotent)
     */
    public function issueVoteFromTransaction(Transaction $transaction): ?VoteLedger
    {
        return DB::transaction(function () use ($transaction) {
            // Lock transaksi untuk mencegah race condition
            $transaction = Transaction::query()
                ->where('id', '=', $transaction->id)
                ->lockForUpdate()
                ->first();

            if ($transaction->status !== 'PAID') {
                return null;
            }

            // IDEMPOTENCY CHECK AT DATABASE LEVEL:
            // Cek apakah transaksi ini sudah pernah memicu penerbitan Vote Ledger
            $existingLedger = VoteLedger::query()
                ->where('transaction_id', '=', $transaction->id)
                ->where('source_type', '=', 'PAID_TRANSACTION')
                ->first();

            if ($existingLedger) {
                return $existingLedger;
            }

            // Terbitkan Vote Ledger resmi
            return VoteLedger::create([
                'event_id' => $transaction->event_id,
                'candidate_id' => $transaction->candidate_id,
                'transaction_id' => $transaction->id,
                'source_type' => 'PAID_TRANSACTION',
                'source_reference' => $transaction->invoice_number,
                'vote_amount' => $transaction->vote_quantity,
                'reason' => "Paid transaction {$transaction->invoice_number}",
                'created_at' => now(),
            ]);
        });
    }

    /**
     * Penyesuaian vote manual oleh Admin (Audit Trail)
     */
    public function createAdminAdjustment(int $eventId, int $candidateId, int $voteAmount, string $reason, int $adminId): VoteLedger
    {
        return VoteLedger::create([
            'event_id' => $eventId,
            'candidate_id' => $candidateId,
            'transaction_id' => null,
            'source_type' => 'ADMIN_ADJUSTMENT',
            'source_reference' => 'ADMIN-' . $adminId . '-' . time(),
            'vote_amount' => $voteAmount,
            'reason' => $reason,
            'created_by_admin_id' => $adminId,
            'created_at' => now(),
        ]);
    }
}