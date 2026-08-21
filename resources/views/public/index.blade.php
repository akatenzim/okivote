@extends('public.layout')

@section('title', 'OkiVote — Cari & Pilih Jagoanmu')

@section('content')
<div class="space-y-7">

    <!-- Hero / Headline Section -->
    <div class="space-y-2 pt-2">
        <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-[#EFECE6] border border-[#DCD7CD] text-[10px] font-bold text-[#C85A32] tracking-wide uppercase">
            <span class="w-1.5 h-1.5 rounded-full bg-[#C85A32] animate-pulse"></span> Platform Voting Publik
        </div>
        <h1 class="text-3xl font-extrabold text-[#1A1D1A] tracking-tight leading-tight">
            Ajang & Kompetisi <br><span class="text-[#C85A32] italic font-serif font-normal">Favorit Pilihanmu.</span>
        </h1>
        <p class="text-xs text-[#78756E] leading-relaxed max-w-sm">
            Dukung kandidat terbaik secara langsung. Perhitungan transparan, aman, dan tercatat di Vote Ledger.
        </p>
    </div>

    <!-- Custom Editorial Filter Tabs -->
    <div class="flex items-center gap-2 border-b border-[#EBE7DF] pb-3 text-xs font-bold overflow-x-auto no-scrollbar">
        <a href="{{ route('home', ['status' => 'ONGOING']) }}"
           class="px-3.5 py-1.5 rounded-full transition-all whitespace-nowrap {{ $status == 'ONGOING' ? 'bg-[#1A1D1A] text-[#FBF9F5]' : 'text-[#78756E] hover:text-[#1A1D1A] hover:bg-[#EFECE6]' }}">
            ⚡ Sedang Berlangsung
        </a>
        <a href="{{ route('home', ['status' => 'COMING_SOON']) }}"
           class="px-3.5 py-1.5 rounded-full transition-all whitespace-nowrap {{ $status == 'COMING_SOON' ? 'bg-[#1A1D1A] text-[#FBF9F5]' : 'text-[#78756E] hover:text-[#1A1D1A] hover:bg-[#EFECE6]' }}">
            🗓️ Akan Datang
        </a>
        <a href="{{ route('home', ['status' => 'FINISHED']) }}"
           class="px-3.5 py-1.5 rounded-full transition-all whitespace-nowrap {{ $status == 'FINISHED' ? 'bg-[#1A1D1A] text-[#FBF9F5]' : 'text-[#78756E] hover:text-[#1A1D1A] hover:bg-[#EFECE6]' }}">
            🏁 Selesai
        </a>
    </div>

    <!-- Event List (Magazine/Editorial Card Style) -->
    <div class="space-y-6">
        @forelse ($events as $event)
            <a href="{{ route('public.events.show', $event->slug) }}" class="group block bg-white border border-[#EBE7DF] hover:border-[#1A1D1A] rounded-xl overflow-hidden transition-all duration-300 shadow-[0_2px_10px_rgba(0,0,0,0.03)] hover:shadow-[0_8px_25px_rgba(0,0,0,0.08)]">

                <!-- Poster Image Area -->
                <div class="relative aspect-[16/9] bg-[#EFECE6] overflow-hidden">
                    @if($event->poster_path)
                        <img src="{{ asset('storage/' . $event->poster_path) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-[#78756E] font-extrabold text-sm tracking-widest uppercase">
                            OkiVote Official Event
                        </div>
                    @endif

                    <!-- Floating Minimal Badge -->
                    <div class="absolute top-3 left-3 flex gap-2">
                        <span class="px-2.5 py-1 rounded-md text-[10px] font-bold tracking-wider uppercase backdrop-blur-md shadow-sm border
                            {{ $event->status == 'ONGOING' ? 'bg-[#1A1D1A]/90 text-[#FBF9F5] border-black/20' : 'bg-[#EFECE6]/90 text-[#78756E] border-[#DCD7CD]' }}">
                            {{ $event->status }}
                        </span>
                    </div>
                </div>

                <!-- Card Meta Content -->
                <div class="p-4 space-y-3">
                    <div>
                        <span class="text-[10px] font-bold tracking-wider text-[#C85A32] uppercase">Penyelenggara</span>
                        <p class="text-xs font-semibold text-[#78756E]">{{ $event->organizer_name }}</p>
                    </div>

                    <h2 class="font-extrabold text-lg text-[#1A1D1A] group-hover:text-[#C85A32] transition-colors leading-snug line-clamp-2">
                        {{ $event->name }}
                    </h2>

                    <div class="pt-2 border-t border-[#F4F1EA] flex items-center justify-between">
                        <span class="text-[11px] text-[#78756E]">Tarif Voting</span>
                        <span class="text-xs font-extrabold font-mono text-[#1A1D1A] bg-[#FBF9F5] px-2.5 py-1 rounded border border-[#EBE7DF]">
                            Rp {{ number_format($event->vote_price, 0, ',', '.') }} <span class="text-[10px] font-normal text-[#78756E]">/ vote</span>
                        </span>
                    </div>
                </div>
            </a>
        @empty
            <div class="text-center py-12 px-4 bg-white border border-dashed border-[#DCD7CD] rounded-xl space-y-2">
                <div class="text-2xl">📭</div>
                <p class="text-xs font-bold text-[#1A1D1A]">Belum Ada Event Aktif</p>
                <p class="text-[11px] text-[#78756E]">Saat ini tidak ada kompetisi dengan kategori ini.</p>
            </div>
        @endforelse
    </div>

</div>
@endsection