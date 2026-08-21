@extends('public.layout')

@section('title', "Vote {$candidate->name} — {$event->name}")

@section('og_meta')
    <meta property="og:title" content="{{ $ogData['title'] }}" />
    <meta property="og:description" content="{{ $ogData['description'] }}" />
    <meta property="og:image" content="{{ $ogData['image'] }}" />
    <meta property="og:url" content="{{ $ogData['url'] }}" />
    <meta property="og:type" content="website" />
@endsection

@section('content')
<div class="space-y-7">
    {{-- Back Link --}}
    <a href="{{ route('public.events.show', $event->slug) }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-[#78756E] hover:text-[#1A1D1A] transition-colors">
        ← Kembali ke {{ $event->name }}
    </a>

    {{-- Card Profil Utama --}}
    <div class="bg-white border border-[#EBE7DF] rounded-xl p-5 sm:p-6 space-y-5 text-center shadow-[0_2px_10px_rgba(0,0,0,0.02)]">

        {{-- Profile Photo with Editorial Badge --}}
        <div class="relative w-40 h-52 mx-auto rounded-lg overflow-hidden bg-[#EFECE6] border border-[#EBE7DF] shadow-md">
            <img src="{{ asset('storage/' . $candidate->profile_photo_path) }}" class="w-full h-full object-cover" alt="{{ $candidate->name }}">
            <div class="absolute top-2.5 left-2.5 bg-[#1A1D1A]/90 backdrop-blur-md text-[#FBF9F5] font-extrabold text-[11px] px-2.5 py-0.5 rounded border border-white/10 font-mono">
                #{{ $candidate->candidate_number }}
            </div>
        </div>

        {{-- Name & Region --}}
        <div class="space-y-1">
            <h1 class="text-2xl font-extrabold text-[#1A1D1A] tracking-tight leading-snug">{{ $candidate->name }}</h1>
            <p class="text-xs font-bold text-[#C85A32] uppercase tracking-wider">{{ $candidate->region ?? 'Peserta ' . $event->name }}</p>
        </div>

        {{-- Biography --}}
        @if($candidate->biography)
            <p class="text-xs text-[#78756E] leading-relaxed bg-[#FBF9F5] p-3.5 rounded-lg border border-[#EBE7DF] text-left">
                {{ $candidate->biography }}
            </p>
        @endif

        {{-- Action & Checkout Modal Wrapper --}}
        <div x-data="{ open: false, qty: 10, price: {{ $event->vote_price }} }" x-cloak class="space-y-2.5 pt-2">

            {{-- Handling Status Event pada Tombol Eksekusi --}}
            @if($event->status === 'ONGOING')
                <button @click="open = true" class="w-full py-3 bg-[#1A1D1A] hover:bg-[#C85A32] text-[#FBF9F5] font-bold rounded-lg text-xs tracking-wide transition-colors duration-200 shadow-sm">
                    ⚡ VOTE SEKARANG
                </button>
            @elseif($event->status === 'SUSPENDED')
                <div class="w-full py-3 bg-[#FFF5F2] text-[#C85A32] border border-[#FCD2C4] font-extrabold rounded-lg text-xs text-center flex items-center justify-center gap-2">
                    <span>🔒</span>
                    <span>VOTING DITUTUP SEMENTARA OLEH PANITIA</span>
                </div>
            @elseif($event->status === 'FINISHED')
                <div class="w-full py-3 bg-[#FBF9F5] text-[#78756E] border border-[#EBE7DF] font-bold rounded-lg text-xs text-center flex items-center justify-center gap-2">
                    <span>🏁</span>
                    <span>PERIODE VOTING TELAH SELESAI</span>
                </div>
            @else
                <div class="w-full py-3 bg-[#FBF9F5] text-[#78756E] border border-[#EBE7DF] font-bold rounded-lg text-xs text-center">
                    ⏳ PERIODE VOTING BELUM DIBUKA
                </div>
            @endif

            <button onclick="shareLink()" class="w-full py-2.5 bg-[#FBF9F5] hover:bg-[#EFECE6] text-[#1A1D1A] font-bold rounded-lg text-xs border border-[#EBE7DF] transition-colors duration-200">
                📲 Bagikan Profil (WhatsApp / Salin Link)
            </button>

            {{-- Modal Overlay & Slide-up Form (Hanya aktif jika ONGOING) --}}
            @if($event->status === 'ONGOING')
                <div x-show="open" class="fixed inset-0 z-50 bg-[#1A1D1A]/60 backdrop-blur-sm flex items-end sm:items-center justify-center p-0 sm:p-4">
                    <div @click.away="open = false" class="bg-white border border-[#EBE7DF] w-full max-w-md rounded-t-2xl sm:rounded-2xl p-6 space-y-5 max-h-[90vh] overflow-y-auto text-left shadow-2xl">

                        {{-- Modal Header --}}
                        <div class="flex justify-between items-center border-b border-[#EBE7DF] pb-3">
                            <div class="space-y-0.5">
                                <span class="text-[10px] font-bold text-[#C85A32] uppercase">Form Dukungan</span>
                                <h3 class="font-extrabold text-base text-[#1A1D1A]">Vote {{ $candidate->name }}</h3>
                            </div>
                            <button @click="open = false" class="text-[#78756E] hover:text-[#1A1D1A] font-bold text-lg p-1">✕</button>
                        </div>

                        <form action="{{ route('public.checkout.store') }}" method="POST" class="space-y-4">
                            @csrf
                            <input type="hidden" name="candidate_id" value="{{ $candidate->id }}">

                            {{-- Preset Vote Quantity --}}
                            <div class="space-y-2">
                                <label class="block text-xs font-bold text-[#1A1D1A]">Pilih Paket Vote *</label>
                                <div class="grid grid-cols-3 gap-2 text-xs">
                                    <template x-for="preset in [10, 25, 50, 100, 250, 500]">
                                        <button type="button" @click="qty = preset"
                                                :class="qty == preset ? 'bg-[#1A1D1A] text-[#FBF9F5] font-bold border-[#1A1D1A]' : 'bg-[#FBF9F5] text-[#78756E] border-[#EBE7DF] hover:bg-[#EFECE6]'"
                                                class="py-2 rounded-lg border text-center transition-all font-mono">
                                            <span x-text="preset"></span> Vote
                                        </button>
                                    </template>
                                </div>
                                <input type="number" name="vote_quantity" x-model="qty" required min="1"
                                       class="w-full px-3 py-2 bg-[#FBF9F5] border border-[#EBE7DF] rounded-lg text-xs text-[#1A1D1A] font-mono font-extrabold focus:outline-none focus:border-[#1A1D1A]">
                            </div>

                            {{-- Form Input Identitas Voter (OPSIONAL / ANONYMOUS) --}}
                            <div class="space-y-3">
                                <div>
                                    <div class="flex justify-between items-center mb-1">
                                        <label class="block text-xs font-bold text-[#1A1D1A]">Nama Lengkap (Opsional)</label>
                                        <span class="text-[10px] text-[#78756E]">(Default: Anonymous)</span>
                                    </div>
                                    <input type="text" name="voter_name" placeholder="Kosongkan jika ingin anonim"
                                           class="w-full px-3 py-2 bg-[#FBF9F5] border border-[#EBE7DF] rounded-lg text-xs text-[#1A1D1A] focus:outline-none focus:border-[#1A1D1A]">
                                </div>

                                <div>
                                    <div class="flex justify-between items-center mb-1">
                                        <label class="block text-xs font-bold text-[#1A1D1A]">No. WhatsApp (Opsional)</label>
                                    </div>
                                    <input type="tel" name="voter_phone" placeholder="081234567890"
                                           class="w-full px-3 py-2 bg-[#FBF9F5] border border-[#EBE7DF] rounded-lg text-xs text-[#1A1D1A] focus:outline-none focus:border-[#1A1D1A]">
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-[#1A1D1A] mb-1">Pesan Dukungan (Opsional)</label>
                                    <textarea name="support_message" rows="2" placeholder="Semangat menuju crown!"
                                              class="w-full px-3 py-2 bg-[#FBF9F5] border border-[#EBE7DF] rounded-lg text-xs text-[#1A1D1A] focus:outline-none focus:border-[#1A1D1A]"></textarea>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-[#1A1D1A] mb-1">Metode Pembayaran *</label>
                                    <select name="payment_method" required class="w-full px-3 py-2 bg-[#FBF9F5] border border-[#EBE7DF] rounded-lg text-xs text-[#1A1D1A] font-bold focus:outline-none focus:border-[#1A1D1A]">
                                        <option value="QRIS">QRIS (GoPay, OVO, ShopeePay, BCA)</option>
                                        <option value="VA_BCA">Virtual Account BCA</option>
                                        <option value="VA_MANDIRI">Virtual Account Mandiri</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Total Ringkasan Tagihan --}}
                            <div class="p-3.5 bg-[#FBF9F5] rounded-lg border border-[#EBE7DF] flex justify-between items-center text-xs">
                                <span class="text-[#78756E] font-medium">Total Pembayaran:</span>
                                <span class="font-extrabold text-[#C85A32] text-sm font-mono" x-text="'Rp ' + (qty * price).toLocaleString('id-ID')"></span>
                            </div>

                            <button type="submit" class="w-full py-3 bg-[#1A1D1A] hover:bg-[#C85A32] text-[#FBF9F5] font-bold rounded-lg text-xs transition-colors duration-200">
                                Lanjut ke Pembayaran →
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Supporter Feed Widget --}}
    <div class="bg-white border border-[#EBE7DF] rounded-xl p-5 space-y-4 text-left shadow-[0_2px_10px_rgba(0,0,0,0.02)]">
        <div class="border-b border-[#EBE7DF] pb-3 flex justify-between items-center">
            <h3 class="font-extrabold text-sm text-[#1A1D1A]">
                💬 Pesan Dukungan
            </h3>
            <span class="text-[10px] font-extrabold font-mono text-[#C85A32] bg-[#FBF9F5] border border-[#EBE7DF] px-2.5 py-1 rounded-md">
                {{ number_format($candidateVotes) }} Vote Masuk
            </span>
        </div>

        <div class="space-y-3 max-h-64 overflow-y-auto pr-1 no-scrollbar">
            @forelse($supporters as $supporter)
                <div class="p-3.5 bg-[#FBF9F5] rounded-lg border border-[#EBE7DF] space-y-1.5 text-xs">
                    <div class="flex justify-between items-center">
                        <span class="font-extrabold text-[#1A1D1A]">
                            {{ ($supporter->is_anonymous || $supporter->voter_name === 'Anonymous') ? 'Anonymous Supporter' : $supporter->voter_name }}
                        </span>
                        <span class="text-[10px] font-mono font-extrabold text-[#1A1D1A] bg-white border border-[#EBE7DF] px-2 py-0.5 rounded">
                            +{{ number_format($supporter->vote_quantity) }} Vote
                        </span>
                    </div>
                    @if($supporter->support_message)
                        <p class="text-[#78756E] italic text-[11px] leading-relaxed">"{{ $supporter->support_message }}"</p>
                    @endif
                </div>
            @empty
                <div class="text-center py-6 text-[#78756E] text-xs space-y-1">
                    <p class="font-bold text-[#1A1D1A]">Belum Ada Pesan</p>
                    <p>Jadilah pendukung pertama yang memberikan suara!</p>
                </div>
            @endforelse
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
</script>
@endsection