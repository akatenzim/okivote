@extends('public.layout')

@section('title', 'Instruksi Pembayaran — ' . $transaction->invoice_number)

@section('content')
<div class="bg-white border border-[#EBE7DF] rounded-xl p-6 text-center space-y-6 shadow-[0_2px_10px_rgba(0,0,0,0.02)]">

    {{-- Header Invoice --}}
    <div class="space-y-2">
        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#FBF9F5] border border-[#EBE7DF] text-[10px] font-extrabold font-mono text-[#C85A32] tracking-wider uppercase">
            <span class="w-1.5 h-1.5 rounded-full bg-[#C85A32]"></span> {{ $transaction->invoice_number }}
        </div>
        <h1 class="text-xl font-extrabold text-[#1A1D1A] tracking-tight">Instruksi Pembayaran</h1>
        <p class="text-xs text-[#78756E]">Selesaikan transaksi sebelum batas waktu berakhir</p>
    </div>

    {{-- Detail Pembelian --}}
    <div class="bg-[#FBF9F5] p-4 rounded-lg border border-[#EBE7DF] text-left text-xs space-y-2.5">
        <div class="flex justify-between items-center">
            <span class="text-[#78756E]">Kandidat Pilihan</span>
            <span class="font-extrabold text-[#1A1D1A]">{{ $transaction->candidate->name }}</span>
        </div>
        <div class="flex justify-between items-center">
            <span class="text-[#78756E]">Jumlah Dukungan</span>
            <span class="font-extrabold font-mono text-[#1A1D1A]">+{{ number_format($transaction->vote_quantity) }} Vote</span>
        </div>
        <div class="flex justify-between items-center border-t border-[#EBE7DF] pt-2.5 text-sm">
            <span class="font-bold text-[#1A1D1A]">Total Tagihan</span>
            <span class="font-extrabold font-mono text-[#C85A32]">Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}</span>
        </div>
    </div>

    {{-- QRIS Instruksi --}}
    <div class="space-y-3 bg-[#FBF9F5] p-5 rounded-lg border border-[#EBE7DF]">
        <span class="text-xs font-bold text-[#1A1D1A] block">Scan Kode QRIS Pembayaran</span>

        <div class="bg-white p-3.5 rounded-lg border border-[#EBE7DF] inline-block shadow-sm">
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ $transaction->invoice_number }}" class="w-44 h-44 mx-auto">
        </div>

        <p class="text-[10px] text-[#78756E] leading-relaxed">
            Mendukung GoPay, OVO, Dana, ShopeePay, LinkAja, dan seluruh aplikasi M-Banking QRIS.
        </p>
    </div>

    {{-- Simulasi Webhook Button (Hanya Lingkungan Lokal/Dev) --}}
    @if(app()->environment('local', 'testing'))
        <div class="pt-4 border-t border-[#EBE7DF] space-y-2">
            <p class="text-[10px] font-bold text-[#78756E] uppercase tracking-wider">Dev Sandbox Tool</p>
            <form action="{{ route('webhook.dummy') }}" method="POST" target="_blank">
                @csrf
                <input type="hidden" name="invoice_number" value="{{ $transaction->invoice_number }}">
                <input type="hidden" name="amount" value="{{ $transaction->grand_total }}">
                <input type="hidden" name="status" value="PAID">
                <button type="submit" class="w-full py-2.5 bg-[#1A1D1A] hover:bg-[#C85A32] text-[#FBF9F5] rounded-lg text-xs font-bold transition-colors duration-200">
                    ⚡ Simulasi Webhook Success (PAID)
                </button>
            </form>
        </div>
    @endif
</div>
@endsection