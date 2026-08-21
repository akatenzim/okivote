@extends('public.layout')

@section('title', $event->name)

@section('content')
<div class="space-y-6">
    {{-- Back Link --}}
    <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-[#78756E] hover:text-[#1A1D1A] transition-colors">
        ← Kembali ke Daftar Event
    </a>

    {{-- Header Banner Event --}}
    <div class="bg-white border border-[#EBE7DF] rounded-xl overflow-hidden shadow-[0_2px_10px_rgba(0,0,0,0.02)]">
        <div class="relative aspect-[16/7] bg-[#EFECE6] overflow-hidden">
            @if($event->poster_path)
                <img src="{{ asset('storage/' . $event->poster_path) }}" class="w-full h-full object-cover">
            @else
                <div class="w-full h-full flex items-center justify-center text-[#78756E] font-extrabold text-sm tracking-widest uppercase">
                    {{ $event->name }}
                </div>
            @endif

            <div class="absolute top-3 left-3">
                <span class="px-2.5 py-1 rounded-md text-[10px] font-bold tracking-wider uppercase backdrop-blur-md shadow-sm border
                    @if($event->status === 'ONGOING') bg-[#1A1D1A]/90 text-[#FBF9F5] border-black/20
                    @elseif($event->status === 'SUSPENDED') bg-[#FFF5F2]/90 text-[#C85A32] border-[#FCD2C4]
                    @else bg-[#EFECE6]/90 text-[#78756E] border-[#DCD7CD] @endif">
                    {{ $event->status === 'SUSPENDED' ? 'DITUTUP SEMENTARA' : $event->status }}
                </span>
            </div>
        </div>

        <div class="p-5 space-y-3">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-[#F4F1EA] pb-3">
                <div>
                    <span class="text-[10px] font-bold tracking-wider text-[#C85A32] uppercase">Penyelenggara</span>
                    <p class="text-xs font-bold text-[#1A1D1A]">{{ $event->organizer_name }}</p>
                </div>
                <div class="text-left sm:text-right">
                    <span class="text-[10px] font-bold tracking-wider text-[#78756E] uppercase">Total Suara Masuk</span>
                    <p class="text-sm font-extrabold font-mono text-[#C85A32]">{{ number_format($totalEventVotes) }} Vote</p>
                </div>
            </div>

            <h1 class="text-2xl font-extrabold text-[#1A1D1A] tracking-tight leading-snug">{{ $event->name }}</h1>

            @if($event->description)
                <p class="text-xs text-[#78756E] leading-relaxed">{{ $event->description }}</p>
            @endif
        </div>
    </div>

    {{-- Kategori Filter (Jika Ada) --}}
    @if($event->categories->isNotEmpty())
        <div class="flex items-center gap-2 border-b border-[#EBE7DF] pb-3 text-xs font-bold overflow-x-auto no-scrollbar">
            <a href="{{ route('public.events.show', $event->slug) }}"
               class="px-3.5 py-1.5 rounded-full transition-all whitespace-nowrap {{ !$selectedCategory ? 'bg-[#1A1D1A] text-[#FBF9F5]' : 'text-[#78756E] hover:text-[#1A1D1A] hover:bg-[#EFECE6]' }}">
                Semua Kategori
            </a>
            @foreach($event->categories as $category)
                <a href="{{ route('public.events.show', [$event->slug, 'category' => $category->id]) }}"
                   class="px-3.5 py-1.5 rounded-full transition-all whitespace-nowrap {{ $selectedCategory == $category->id ? 'bg-[#1A1D1A] text-[#FBF9F5]' : 'text-[#78756E] hover:text-[#1A1D1A] hover:bg-[#EFECE6]' }}">
                    {{ $category->name }}
                </a>
            @endforeach
        </div>
    @endif

    {{-- Daftar Kandidat / Leaderboard --}}
    <div class="space-y-4">
        <h2 class="text-xs font-extrabold tracking-wider uppercase text-[#C85A32]">Daftar Peserta & Rangking Sementara</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @forelse($candidates as $candidate)
                <div class="bg-white border border-[#EBE7DF] rounded-xl p-4 flex gap-4 items-center shadow-[0_2px_8px_rgba(0,0,0,0.02)] hover:border-[#1A1D1A] transition-colors">
                    <div class="relative w-16 h-20 rounded-lg overflow-hidden bg-[#EFECE6] shrink-0 border border-[#EBE7DF]">
                        <img src="{{ asset('storage/' . $candidate->profile_photo_path) }}" class="w-full h-full object-cover">
                        <span class="absolute top-1 left-1 bg-[#1A1D1A]/90 text-white font-mono font-extrabold text-[9px] px-1.5 py-0.5 rounded">
                            #{{ $candidate->candidate_number }}
                        </span>
                    </div>

                    <div class="flex-1 space-y-1.5 min-w-0">
                        <div>
                            <h3 class="font-extrabold text-sm text-[#1A1D1A] truncate">{{ $candidate->name }}</h3>
                            <p class="text-[10px] font-bold text-[#78756E] uppercase truncate">{{ $candidate->region ?? '-' }}</p>
                        </div>

                        {{-- Progress Bar Vote --}}
                        <div class="space-y-1">
                            <div class="flex justify-between items-center text-[10px] font-mono">
                                <span class="font-bold text-[#C85A32]">{{ number_format($candidate->total_votes) }} Vote</span>
                                <span class="text-[#78756E]">{{ $candidate->percentage }}%</span>
                            </div>
                            <div class="w-full h-1.5 bg-[#EFECE6] rounded-full overflow-hidden">
                                <div class="h-full bg-[#1A1D1A] rounded-full transition-all duration-500" style="width: {{ $candidate->percentage }}%"></div>
                            </div>
                        </div>

                        <a href="{{ route('public.candidates.show', [$event->slug, $candidate->slug]) }}"
                           class="inline-block pt-1 text-[11px] font-extrabold text-[#1A1D1A] hover:text-[#C85A32] transition-colors">
                            Beri Dukungan →
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-8 text-[#78756E] text-xs">
                    Belum ada kandidat terdaftar pada kategori ini.
                </div>
            @endforelse
        </div>
    </div>
</div>

{{-- ⚡ Compact Live Vote Toast Notification (Top-Center, Light Editorial Style) --}}
@if(isset($recentVotes) && $recentVotes->isNotEmpty())
<div x-data="{
        votes: {{ json_encode($recentVotes) }},
        currentIndex: 0,
        show: false,
        init() {
            if(this.votes.length > 0) {
                setTimeout(() => { this.show = true; }, 1200);
                setInterval(() => {
                    this.show = false;
                    setTimeout(() => {
                        this.currentIndex = (this.currentIndex + 1) % this.votes.length;
                        this.show = true;
                    }, 400);
                }, 4500);
            }
        }
    }"
    x-show="show"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 -translate-y-4 scale-95"
    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 translate-y-0 scale-100"
    x-transition:leave-end="opacity-0 -translate-y-4 scale-95"
    x-cloak
    class="fixed top-4 left-1/2 -translate-x-1/2 z-50 bg-[#FBF9F5] text-[#1A1D1A] border border-[#EBE7DF] rounded-full px-3.5 py-1.5 shadow-[0_4px_20px_rgba(0,0,0,0.08)] flex items-center gap-2.5 max-w-sm pointer-events-none">

    <div class="w-2 h-2 rounded-full bg-[#C85A32] animate-ping shrink-0"></div>

    <div class="text-[11px] leading-tight truncate">
        <span class="font-extrabold text-[#1A1D1A]" x-text="votes[currentIndex].voter"></span>
        <span class="text-[#78756E]"> beri </span>
        <span class="font-mono font-extrabold text-[#C85A32]" x-text="'+' + votes[currentIndex].qty + ' Vote'"></span>
        <span class="text-[#78756E]"> untuk </span>
        <strong class="font-bold text-[#1A1D1A]" x-text="votes[currentIndex].candidate"></strong>
    </div>
</div>
@endif
@endsection