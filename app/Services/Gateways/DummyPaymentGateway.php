<?php

namespace App\Services\Gateways;

use App\Contracts\PaymentGatewayInterface;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DummyPaymentGateway implements PaymentGatewayInterface
{
    public function createCharge(Transaction $transaction, array $payload): array
    {
        // Simulasi respon sukses dari Payment Gateway
        $reference = 'MOCK-' . strtoupper(Str::random(10));

        return [
            'success' => true,
            'gateway' => 'DUMMY_GATEWAY',
            'gateway_reference' => $reference,
            'payment_method' => $payload['payment_method'] ?? 'QRIS',
            'payment_url' => url('/mock-payment/' . $transaction->invoice_number),
            'qr_code_url' => 'https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=' . $reference,
            'raw_response' => [
                'status' => 'PENDING',
                'reference' => $reference,
                'created_at' => now()->toIso8601String(),
            ],
        ];
    }

    public function verifyWebhookSignature(Request $request): bool
    {
        // Pada mock provider, kita validasi token sederhana di header / body
        return $request->header('X-Mock-Signature') === config('services.dummy_gateway.secret', 'okivote-secret');
    }

    public function mapPaymentStatus(string $gatewayStatus): string
    {
        return match (strtoupper($gatewayStatus)) {
            'SETTLEMENT', 'SUCCESS', 'PAID' => 'PAID',
            'EXPIRED' => 'EXPIRED',
            'CANCELLED' => 'CANCELLED',
            default => 'FAILED',
        };
    }
}