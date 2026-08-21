@extends('public.layout')

@section('title', $event->name . ' — OkiVote')

@section('content')
<div class="space-y-7">
    {{-- Header Event & Meta Info --}}
    <div class="bg-white border border-[#EBE7DF] rounded-xl p-5 space-y-4 shadow-[0_2px_10px_rgba(0,0,0,0.02)]">
        <div class="space-y-1.5">
            <span class="text-[10px] font-bold tracking-widest text-[#C85A32] uppercase">Official Event Page</span>
            <h1 class="text-2xl font-extrabold text-[#1A1D1A] tracking-tight leading-tight">{{ $event->name }}</h1>
        </div>

        <p class="text-xs text-[#78756E] leading-relaxed">{{ $event->description ?? 'Pilih kandidat favoritmu dan berikan dukungan sekarang!' }}</p>

        <div class="pt-3 border-t border-[#F4F1EA] flex items-center justify-between text-xs">
            <span class="text-[#78756E] font-medium">Tarif Voting Resmi</span>
            <span class="font-extrabold font-mono text-[#1A1D1A] bg-[#FBF9F5] px-3 py-1 rounded-md border border-[#EBE7DF]">
                Rp {{ number_format($event->vote_price, 0, ',', '.') }} <span class="text-[10px] font-normal text-[#78756E]">/ vote</span>
            </span>
        </div>
    </div>

    {{-- Kategori Filter --}}
    @if($event->categories->count() > 0)
        <div class="flex items-center gap-2 overflow-x-auto pb-1 text-xs font-bold no-scrollbar">
            <a href="{{ route('public.events.show', $event->slug) }}"
               class="px-3.5 py-1.5 rounded-full transition-all whitespace-nowrap {{ !$selectedCategory ? 'bg-[#1A1D1A] text-[#FBF9F5]' : 'bg-white text-[#78756E] border border-[#EBE7DF] hover:bg-[#EFECE6]' }}">
                Semua Kategori
            </a>
            @foreach($event->categories as $category)
                <a href="{{ route('public.events.show', [$event->slug, 'category' => $category->id]) }}"
                   class="px-3.5 py-1.5 rounded-full transition-all whitespace-nowrap {{ $selectedCategory == $category->id ? 'bg-[#1A1D1A] text-[#FBF9F5]' : 'bg-white text-[#78756E] border border-[#EBE7DF] hover:bg-[#EFECE6]' }}">
                    {{ $category->name }}
                </a>
            @endforeach
        </div>
    @endif

    {{-- Grid Kandidat --}}
    <div class="grid grid-cols-2 gap-4">
        @forelse($candidates as $candidate)
            <div class="group bg-white border border-[#EBE7DF] hover:border-[#1A1D1A] rounded-xl overflow-hidden flex flex-col justify-between transition-all duration-300 shadow-[0_2px_8px_rgba(0,0,0,0.02)] hover:shadow-[0_6px_20px_rgba(0,0,0,0.06)]">

                {{-- Foto Kandidat --}}
                <div class="relative aspect-[3/4] bg-[#EFECE6] overflow-hidden">
                    <img src="{{ asset('storage/' . $candidate->profile_photo_path) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out">

                    <div class="absolute top-2.5 left-2.5 bg-[#1A1D1A]/90 backdrop-blur-md text-[#FBF9F5] font-extrabold text-[10px] px-2 py-0.5 rounded border border-white/10 tracking-wide font-mono">
                        #{{ $candidate->candidate_number }}
                    </div>
                </div>

                {{-- Detail & Leaderboard --}}
                <div class="p-3.5 space-y-3 flex-1 flex flex-col justify-between">
                    <div class="space-y-0.5">
                        <h3 class="font-extrabold text-sm text-[#1A1D1A] group-hover:text-[#C85A32] transition-colors line-clamp-1">{{ $candidate->name }}</h3>
                        <p class="text-[10px] font-semibold text-[#78756E]">{{ $candidate->region ?? '-' }}</p>
                    </div>

                    {{-- Leaderboard Vote Progress Bar --}}
                    <div class="space-y-1.5 bg-[#FBF9F5] p-2.5 rounded-lg border border-[#EBE7DF]">
                        <div class="flex justify-between items-center text-[10px] font-extrabold">
                            <span class="text-[#1A1D1A] font-mono">{{ number_format($candidate->total_votes) }} <span class="text-[9px] font-normal text-[#78756E]">Vote</span></span>
                            <span class="text-[#C85A32] font-mono">{{ $candidate->percentage }}%</span>
                        </div>
                        <div class="w-full bg-[#EFECE6] h-1.5 rounded-full overflow-hidden">
                            <div class="bg-[#C85A32] h-full rounded-full transition-all duration-500" style="width: {{ $candidate->percentage }}%"></div>
                        </div>
                    </div>

                    {{-- Action Button --}}
                    <a href="{{ route('public.candidates.show', [$event->slug, $candidate->slug]) }}"
                       class="block w-full text-center py-2 bg-[#1A1D1A] hover:bg-[#C85A32] font-bold text-[#FBF9F5] rounded-lg text-xs transition-colors duration-200">
                        Beri Vote
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-2 text-center py-12 bg-white border border-dashed border-[#DCD7CD] rounded-xl space-y-1">
                <p class="text-xs font-bold text-[#1A1D1A]">Belum Ada Kandidat</p>
                <p class="text-[11px] text-[#78756E]">Belum ada kandidat terdaftar pada kategori ini.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection