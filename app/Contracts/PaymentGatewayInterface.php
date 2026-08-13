<?php

namespace App\Contracts;

use App\Models\Transaction;
use Illuminate\Http\Request;

interface PaymentGatewayInterface
{
    /**
     * Membuat request charge/instruksi pembayaran ke Gateway.
     */
    public function createCharge(Transaction $transaction, array $payload): array;

    /**
     * Memverifikasi keabsahan signature/callback dari Webhook Gateway.
     */
    public function verifyWebhookSignature(Request $request): bool;

    /**
     * Memetakan status spesifik dari Gateway ke status internal OkiVote.
     */
    public function mapPaymentStatus(string $gatewayStatus): string;
}