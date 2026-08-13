<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCheckoutRequest;
use App\Models\Transaction;
use App\Services\TransactionService;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function store(StoreCheckoutRequest $request, TransactionService $transactionService)
    {
        try {
            $transaction = $transactionService->createTransaction($request->validated());

            return redirect()->route('public.checkout.success', $transaction->invoice_number);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    // Halaman Instruksi Pembayaran (QRIS / VA)
    public function show($invoiceNumber)
    {
        $transaction = Transaction::whereInvoiceNumber($invoiceNumber)
            ->with(['event', 'candidate', 'payments'])
            ->firstOrFail();

        $latestPayment = $transaction->payments()->latest()->first();

        return view('public.checkout.show', compact('transaction', 'latestPayment'));
    }
}