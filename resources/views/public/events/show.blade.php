@extends('public.layout')

@section('title', $event->name . ' - OkiVote')

@section('content')
<div class="space-y-6">
    {{-- Header Event --}}
    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-4 space-y-3">
        <h1 class="text-xl font-black text-slate-100">{{ $event->name }}</h1>
        <p class="text-xs text-slate-400 leading-relaxed">{{ $event->description ?? 'Pilih kandidat favoritmu dan berikan dukungan sekarang!' }}</p>
        <div class="text-xs bg-slate-950 p-3 rounded-xl border border-slate-800/80 flex items-center justify-between text-slate-300">
            <span>Harga per Vote:</span>
            <span class="font-bold text-amber-400 font-mono">Rp {{ number_format($event->vote_price, 0, ',', '.') }}</span>
        </div>
    </div>

    {{-- Kategori Filter jika ada --}}
    @if($event->categories->count() > 0)
        <div class="flex gap-2 overflow-x-auto pb-1 text-xs">
            <a href="{{ route('public.events.show', $event->slug) }}"
               class="px-3 py-1.5 rounded-full border whitespace-nowrap {{ !$selectedCategory ? 'bg-amber-500 text-slate-950 font-bold border-amber-500' : 'bg-slate-900 text-slate-400 border-slate-800' }}">
                Semua Kategori
            </a>
            @foreach($event->categories as $category)
                <a href="{{ route('public.events.show', [$event->slug, 'category' => $category->id]) }}"
                   class="px-3 py-1.5 rounded-full border whitespace-nowrap {{ $selectedCategory == $category->id ? 'bg-amber-500 text-slate-950 font-bold border-amber-500' : 'bg-slate-900 text-slate-400 border-slate-800' }}">
                    {{ $category->name }}
                </a>
            @endforeach
        </div>
    @endif

    {{-- Grid Kandidat --}}
    <div class="grid grid-cols-2 gap-3">
        @forelse($candidates as $candidate)
            <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden flex flex-col justify-between">
                <div class="relative aspect-[3/4] bg-slate-800">
                    <img src="{{ asset('storage/' . $candidate->profile_photo_path) }}" class="w-full h-full object-cover">
                    <div class="absolute top-2 left-2 bg-slate-950/80 backdrop-blur-md text-amber-400 font-black text-xs px-2 py-0.5 rounded-md border border-slate-800">
                        #{{ $candidate->candidate_number }}
                    </div>
                </div>
                <div class="p-3 space-y-2 flex-1 flex flex-col justify-between">
                    <div>
                        <h3 class="font-bold text-sm text-slate-100 line-clamp-1">{{ $candidate->name }}</h3>
                        <p class="text-[10px] text-slate-400">{{ $candidate->region ?? '-' }}</p>
                    </div>
                    <a href="{{ route('public.candidates.show', [$event->slug, $candidate->slug]) }}"
                       class="block w-full text-center py-2 bg-amber-500 hover:bg-amber-600 font-bold text-slate-950 rounded-xl text-xs transition">
                        Vote
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-2 text-center py-8 text-slate-500 text-xs">Belum ada kandidat di kategori ini.</div>
        @endforelse
    </div>
</div>
@endsection