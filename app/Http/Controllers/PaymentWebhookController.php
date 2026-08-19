<?php

namespace App\Http\Controllers;

use App\Contracts\PaymentGatewayInterface;
use App\Models\Payment;
use App\Models\PaymentWebhook;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Services\VoteService;

class PaymentWebhookController extends Controller
{
    public function handle(Request $request, PaymentGatewayInterface $gateway, VoteService $voteService)
    {
        $payload = $request->all();
        $gatewayEventId = $request->header('X-Event-ID') ?? $payload['event_id'] ?? null;

        // 1. Audit Log Webhook Entry
        $webhook = PaymentWebhook::create([
            'gateway' => 'DUMMY_GATEWAY',
            'gateway_event_id' => $gatewayEventId,
            'gateway_reference' => $payload['gateway_reference'] ?? null,
            'payload' => $payload,
            'signature_valid' => $gateway->verifyWebhookSignature($request),
            'processing_status' => 'PENDING',
            'received_at' => now(),
        ]);

        if (!$webhook->signature_valid) {
            $webhook->update(['processing_status' => 'FAILED']);
            return response()->json(['message' => 'Invalid signature'], 400);
        }

        // 2. IDEMPOTENCY CHECK: Jika event webhook ini sudah pernah diproses, langsung tanggapi 200 OK
        $existingProcessed = PaymentWebhook::query()
            ->where('gateway', '=', 'DUMMY_GATEWAY')
            ->where('gateway_event_id', '=', $gatewayEventId)
            ->where('processing_status', '=', 'PROCESSED')
            ->exists();
        if ($existingProcessed) {
            $webhook->update(['processing_status' => 'IGNORED']);
            return response()->json(['message' => 'Webhook already processed']);
        }

        // 3. Find Related Transaction
        $invoiceNumber = $payload['invoice_number'] ?? null;
        $transaction = Transaction::query()
            ->where('invoice_number', '=', $invoiceNumber)
            ->first();

        if (!$transaction) {
            $webhook->update(['processing_status' => 'FAILED']);
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        $webhook->update(['transaction_id' => $transaction->id]);

        // 4. VERIFY MATCH AMOUNT RULE
        $paidAmount = (int) ($payload['amount'] ?? 0);
        if ($paidAmount !== (int) $transaction->grand_total) {
            $webhook->update(['processing_status' => 'FAILED']);
            return response()->json(['message' => 'Amount mismatch anomaly detected'], 422);
        }

        // 5. PROCESS PAYMENT STATUS UPDATE
        $mappedStatus = $gateway->mapPaymentStatus($payload['status'] ?? 'FAILED');

        DB::transaction(function () use ($transaction, $mappedStatus, $payload, $webhook, $voteService) {
            $transaction = Transaction::query()
                ->where('id', '=', $transaction->id)
                ->lockForUpdate()
                ->first();

            if ($transaction->status === 'PENDING' && $mappedStatus === 'PAID') {
                $transaction->update([
                    'status' => 'PAID',
                    'paid_at' => now(),
                ]);

                $transaction->payments()->update([
                    'status' => 'PAID',
                    'paid_at' => now(),
                ]);

                // PANGGIL VOTE SERVICE UNTUK MENERBITKAN VOTE LEDGER!
                $voteService->issueVoteFromTransaction($transaction);
            }

            $webhook->update([
                'processing_status' => 'PROCESSED',
                'processed_at' => now(),
            ]);
        });

        return response()->json(['message' => 'Webhook processed and vote issued successfully']);
    }
}