<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCheckoutRequest;
use App\Models\Candidate;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function store(StoreCheckoutRequest $request)
    {
        $data = $request->validatedWithDefaults();

        $candidate = Candidate::with('event')->findOrFail($data['candidate_id']);

        // 🔒 Security Check: Tolak transaksi jika event sedang SUSPENDED / Tutup Sementara
        if ($candidate->event->status === 'SUSPENDED') {
            return back()->with('error', 'Mohon maaf, sesi voting untuk event ini sedang ditutup sementara oleh panitia.');
        }

        if ($candidate->event->status !== 'ONGOING') {
            return back()->with('error', 'Mohon maaf, periode voting untuk event ini tidak sedang aktif.');
        }

        // Eksekusi Pembuatan Transaksi
        $transaction = DB::transaction(function () use ($data, $candidate) {
            $grandTotal = $data['vote_quantity'] * $candidate->event->vote_price;

            return Transaction::create([
                'invoice_number'  => 'INV-' . strtoupper(Str::random(10)),
                'event_id'        => $candidate->event_id,
                'candidate_id'    => $candidate->id,
                'voter_name'      => $data['voter_name'],
                'voter_phone'     => $data['voter_phone'],
                'is_anonymous'    => $data['is_anonymous'],
                'vote_quantity'   => $data['vote_quantity'],
                'grand_total'     => $grandTotal,
                'support_message' => $data['support_message'] ?? null,
                'payment_method'  => $data['payment_method'],
                'status'          => 'PENDING',
            ]);
        });

        return redirect()->route('public.checkout.show', $transaction->invoice_number);
    }
}