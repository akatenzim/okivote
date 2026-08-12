@extends('public.layout')

@section('title', "Vote {$candidate->name} - {$event->name}")

@section('og_meta')
    <meta property="og:title" content="{{ $ogData['title'] }}" />
    <meta property="og:description" content="{{ $ogData['description'] }}" />
    <meta property="og:image" content="{{ $ogData['image'] }}" />
    <meta property="og:url" content="{{ $ogData['url'] }}" />
    <meta property="og:type" content="website" />
@endsection

@section('content')
<div class="space-y-6">
    <a href="{{ route('public.events.show', $event->slug) }}" class="text-xs text-slate-400 hover:text-amber-400 inline-block mb-2">
        ← Kembali ke {{ $event->name }}
    </a>

    {{-- Card Profil --}}
    <div class="bg-slate-900 border border-slate-800 rounded-3xl p-4 space-y-4 text-center">
        <div class="relative w-36 h-48 mx-auto rounded-2xl overflow-hidden border-2 border-amber-500/50 shadow-xl">
            <img src="{{ asset('storage/' . $candidate->profile_photo_path) }}" class="w-full h-full object-cover">
            <div class="absolute top-2 left-2 bg-amber-500 text-slate-950 font-black text-xs px-2.5 py-0.5 rounded-lg shadow">
                #{{ $candidate->candidate_number }}
            </div>
        </div>

        <div>
            <h1 class="text-xl font-black text-slate-100">{{ $candidate->name }}</h1>
            <p class="text-xs text-amber-400 font-medium">{{ $candidate->region ?? 'Peserta ' . $event->name }}</p>
        </div>

        @if($candidate->biography)
            <p class="text-xs text-slate-400 leading-relaxed bg-slate-950 p-3 rounded-xl border border-slate-800/80">
                {{ $candidate->biography }}
            </p>
        @endif

        {{-- Share Button & CTA Vote --}}
        <div class="space-y-2 pt-2">
            <button onclick="openCheckoutModal()" class="w-full py-3 bg-amber-500 hover:bg-amber-600 text-slate-950 font-black rounded-2xl text-sm shadow-lg shadow-amber-500/10 transition">
                ⚡ VOTE SEKARANG
            </button>
            <button onclick="shareLink()" class="w-full py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-200 font-semibold rounded-2xl text-xs border border-slate-700 transition">
                📲 Bagikan Profil (WhatsApp/Copy)
            </button>
        </div>
    </div>
</div>

<script>
    function shareLink() {
        if (navigator.share) {
            navigator.share({
                title: '{{ $ogData['title'] }}',
                text: '{{ $ogData['description'] }}',
                url: '{{ $ogData['url'] }}'
            });
        } else {
            navigator.clipboard.writeText('{{ $ogData['url'] }}');
            alert('Link profil kandidat berhasil disalin!');
        }
    }

    function openCheckoutModal() {
        alert('Modul Checkout & Payment akan diaktifkan di Sprint 4!');
    }
</script>
@endsection