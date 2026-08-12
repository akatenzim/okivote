@extends('public.layout')

@section('title', 'Buat Event Voting - OkiVote')

@section('content')
<div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 text-center space-y-6">
    <div class="w-12 h-12 bg-amber-500/20 text-amber-400 border border-amber-500/30 rounded-2xl flex items-center justify-center mx-auto text-xl font-bold">
        🚀
    </div>

    <div class="space-y-2">
        <h1 class="text-xl font-black text-slate-100">Jalankan Event Voting Digital Anda!</h1>
        <p class="text-xs text-slate-400 leading-relaxed">
            OkiVote membantu Penyelenggara Event (EO), Pemilihan Duta, dan Kompetisi Talent menjalankan voting online secara transparan, cepat, dan aman.
        </p>
    </div>

    <div class="space-y-3 bg-slate-950 p-4 rounded-2xl border border-slate-800 text-left text-xs text-slate-300">
        <div class="flex items-center gap-2">✅ Setup Event & Kandidat Cepat</div>
        <div class="flex items-center gap-2">✅ Integrasi Payment Gateway (QRIS, VA, E-Wallet)</div>
        <div class="flex items-center gap-2">✅ Leaderboard Real-time & Transparan</div>
    </div>

    <a href="https://wa.me/6281234567890?text=Halo%20Admin%20OkiVote,%20saya%20ingin%20mendaftarkan%20event%20voting"
       target="_blank"
       class="block w-full py-3 bg-emerald-500 hover:bg-emerald-600 text-slate-950 font-bold rounded-2xl text-xs transition shadow-lg shadow-emerald-500/10">
        💬 Hubungi Admin via WhatsApp
    </a>
</div>
@endsection