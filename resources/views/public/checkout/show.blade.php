@extends('public.layout')

@section('title', 'Instruksi Pembayaran - ' . $transaction->invoice_number)

@section('content')
<div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 text-center space-y-6">
    <div>
        <span class="text-[10px] bg-amber-500/20 text-amber-400 font-bold px-3 py-1 rounded-full border border-amber-500/30">
            INVOICE: {{ $transaction->invoice_number }}
        </span>
        <h1 class="text-lg font-bold text-slate-100 mt-3">Instruksi Pembayaran</h1>
        <p class="text-xs text-slate-400">Selesaikan pembayaran sebelum masa berlaku habis</p>
    </div>

    {{-- Detail Pembelian --}}
    <div class="bg-slate-950 p-4 rounded-2xl border border-slate-800 text-left text-xs space-y-2">
        <div class="flex justify-between">
            <span class="text-slate-400">Kandidat:</span>
            <span class="font-bold text-slate-200">{{ $transaction->candidate->name }}</span>
        </div>
        <div class="flex justify-between">
            <span class="text-slate-400">Jumlah Vote:</span>
            <span class="font-bold text-amber-400 font-mono">{{ number_format($transaction->vote_quantity) }} Vote</span>
        </div>
        <div class="flex justify-between border-t border-slate-800 pt-2 text-sm">
            <span class="text-slate-300 font-bold">Total Bayar:</span>
            <span class="font-bold text-amber-400 font-mono">Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}</span>
        </div>
    </div>

    {{-- QRIS / VA Instruksi --}}
    <div class="space-y-3 bg-slate-950 p-4 rounded-2xl border border-slate-800">
        <p class="text-xs font-semibold text-slate-300">Scan QRIS Di Bawah Ini:</p>
        <div class="bg-white p-3 rounded-xl inline-block">
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ $transaction->invoice_number }}" class="w-44 h-44 mx-auto">
        </div>
        <p class="text-[10px] text-slate-500">Mendukung GoPay, OVO, Dana, ShopeePay, dan seluruh M-Banking QRIS.</p>
    </div>

    {{-- Simulasi Webhook Button (Untuk Testing Lokal) --}}
    <div class="pt-4 border-t border-slate-800 space-y-2">
        <p class="text-[10px] text-slate-500">Tombol pengujian simulasi pembayaran (Lokal Sandbox):</p>
        <form action="{{ route('webhook.dummy') }}" method="POST" target="_blank">
            @csrf
            <input type="hidden" name="invoice_number" value="{{ $transaction->invoice_number }}">
            <input type="hidden" name="amount" value="{{ $transaction->grand_total }}">
            <input type="hidden" name="status" value="PAID">
            <button type="submit" class="w-full py-2 bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 rounded-xl text-xs font-bold hover:bg-emerald-500/30">
                ⚡ Simulasi Gateway Webhook SUCCESS
            </button>
        </form>
    </div>
</div>
@endsection