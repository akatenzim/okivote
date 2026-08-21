@extends('public.layout')

@section('title', 'Buat Event Voting — OkiVote')

@section('content')
<div class="space-y-7">
    {{-- Main Container Card --}}
    <div class="bg-white border border-[#EBE7DF] rounded-xl p-6 text-center space-y-6 shadow-[0_2px_10px_rgba(0,0,0,0.02)]">

        {{-- Icon Header --}}
        <div class="w-12 h-12 bg-[#FBF9F5] text-[#C85A32] border border-[#EBE7DF] rounded-xl flex items-center justify-center mx-auto text-xl shadow-sm">
            🚀
        </div>

        {{-- Title & Description --}}
        <div class="space-y-2">
            <span class="text-[10px] font-bold tracking-widest text-[#C85A32] uppercase">Penyelenggara Event & EO</span>
            <h1 class="text-2xl font-extrabold text-[#1A1D1A] tracking-tight leading-snug">
                Jalankan Event Voting <br><span class="text-[#C85A32] italic font-serif font-normal">Digital & Transparan.</span>
            </h1>
            <p class="text-xs text-[#78756E] leading-relaxed max-w-sm mx-auto pt-1">
                OkiVote membantu Penyelenggara Event (EO), Pemilihan Duta Wisata, dan Kompetisi Talent menjalankan voting online dengan sistem Ledger real-time terpercaya.
            </p>
        </div>

        {{-- Feature Highlights --}}
        <div class="space-y-3 bg-[#FBF9F5] p-4 rounded-lg border border-[#EBE7DF] text-left text-xs font-semibold text-[#1A1D1A]">
            <div class="flex items-center gap-2.5">
                <span class="w-1.5 h-1.5 rounded-full bg-[#C85A32]"></span>
                <span>Setup Event & Katalog Kandidat Cepat</span>
            </div>
            <div class="flex items-center gap-2.5">
                <span class="w-1.5 h-1.5 rounded-full bg-[#C85A32]"></span>
                <span>Integrasi Payment Gateway (QRIS, VA, E-Wallet)</span>
            </div>
            <div class="flex items-center gap-2.5">
                <span class="w-1.5 h-1.5 rounded-full bg-[#C85A32]"></span>
                <span>Leaderboard Real-time & Sistem Bagi Hasil Transparan</span>
            </div>
        </div>

        {{-- Call to Action Button --}}
        <div class="pt-2">
            <a href="https://wa.me/6281234567890?text=Halo%20Admin%20OkiVote,%20saya%20ingin%20mendaftarkan%20event%20voting"
               target="_blank"
               class="block w-full py-3 bg-[#1A1D1A] hover:bg-[#C85A32] text-[#FBF9F5] font-bold rounded-lg text-xs transition-colors duration-200 shadow-sm">
                💬 Hubungi Tim Kami via WhatsApp
            </a>
        </div>
    </div>
</div>
@endsection