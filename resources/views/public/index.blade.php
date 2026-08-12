@extends('public.layout')

@section('title', 'OkiVote - Cari & Lakukan Voting Event')

@section('content')
<div class="space-y-6">
    {{-- Hero Banner --}}
    <div class="text-center space-y-2">
        <h1 class="text-2xl font-extrabold text-slate-100">Dukung Jagoanmu Sekarang!</h1>
        <p class="text-xs text-slate-400">Voting transparan, cepat, dan aman untuk berbagai kompetisi favoritmu.</p>
    </div>

    {{-- Filter Tab --}}
    <div class="flex bg-slate-900 p-1 rounded-xl border border-slate-800 text-xs font-semibold">
        <a href="{{ route('home', ['status' => 'ONGOING']) }}"
           class="flex-1 text-center py-2 rounded-lg transition {{ $status == 'ONGOING' ? 'bg-amber-500 text-slate-950 shadow' : 'text-slate-400' }}">
            Sedang Jalan
        </a>
        <a href="{{ route('home', ['status' => 'COMING_SOON']) }}"
           class="flex-1 text-center py-2 rounded-lg transition {{ $status == 'COMING_SOON' ? 'bg-amber-500 text-slate-950 shadow' : 'text-slate-400' }}">
            Akan Datang
        </a>
        <a href="{{ route('home', ['status' => 'FINISHED']) }}"
           class="flex-1 text-center py-2 rounded-lg transition {{ $status == 'FINISHED' ? 'bg-amber-500 text-slate-950 shadow' : 'text-slate-400' }}">
            Selesai
        </a>
    </div>

    {{-- Event Cards --}}
    <div class="space-y-4">
        @forelse ($events as $event)
            <a href="{{ route('public.events.show', $event->slug) }}" class="block group bg-slate-900 border border-slate-800 hover:border-amber-500/50 rounded-2xl overflow-hidden transition">
                <div class="relative aspect-video bg-slate-800">
                    @if($event->poster_path)
                        <img src="{{ asset('storage/' . $event->poster_path) }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-slate-600 font-bold text-sm">OkiVote Event</div>
                    @endif
                    <div class="absolute top-3 left-3">
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold tracking-wider uppercase backdrop-blur-md
                            {{ $event->status == 'ONGOING' ? 'bg-emerald-500/80 text-white' : 'bg-slate-800/80 text-slate-300' }}">
                            {{ $event->status }}
                        </span>
                    </div>
                </div>
                <div class="p-4 space-y-2">
                    <h2 class="font-bold text-base text-slate-100 group-hover:text-amber-400 transition">{{ $event->name }}</h2>
                    <div class="flex items-center justify-between text-xs text-slate-400">
                        <span>EO: {{ $event->organizer_name }}</span>
                        <span class="text-amber-500 font-semibold">Rp {{ number_format($event->vote_price, 0, ',', '.') }}/vote</span>
                    </div>
                </div>
            </a>
        @empty
            <div class="text-center py-12 bg-slate-900/50 border border-slate-800/80 rounded-2xl text-slate-500 text-xs">
                Tidak ada event dengan status ini saat ini.
            </div>
        @endforelse
    </div>
</div>
@endsection