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

        {{-- Modal Checkout Wrapper --}}
        <div x-data="{ open: false, qty: 10, price: {{ $event->vote_price }} }" x-cloak class="space-y-2 pt-2">

            {{-- Tombol Utama yang membuka Modal --}}
            <button @click="open = true" class="w-full py-3 bg-amber-500 hover:bg-amber-600 text-slate-950 font-black rounded-2xl text-sm shadow-lg transition">
                ⚡ VOTE SEKARANG
            </button>

            <button onclick="shareLink()" class="w-full py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-200 font-semibold rounded-2xl text-xs border border-slate-700 transition">
                📲 Bagikan Profil (WhatsApp/Copy)
            </button>

            {{-- Overlay & Form Modal Checkout --}}
            <div x-show="open" class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-sm flex items-end sm:items-center justify-center p-0 sm:p-4">
                <div @click.away="open = false" class="bg-slate-900 border border-slate-800 w-full max-w-md rounded-t-3xl sm:rounded-3xl p-6 space-y-4 max-h-[90vh] overflow-y-auto text-left">
                    <div class="flex justify-between items-center border-b border-slate-800 pb-3">
                        <h3 class="font-bold text-slate-100">Vote {{ $candidate->name }}</h3>
                        <button @click="open = false" class="text-slate-400 hover:text-slate-200">✕</button>
                    </div>

                    <form action="{{ route('public.checkout.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="candidate_id" value="{{ $candidate->id }}">

                        {{-- Preset Vote Quantity --}}
                        <div>
                            <label class="block text-xs font-medium text-slate-400 mb-2">Pilih Jumlah Vote</label>
                            <div class="grid grid-cols-3 gap-2 text-xs">
                                <template x-for="preset in [10, 25, 50, 100, 250, 500]">
                                    <button type="button" @click="qty = preset"
                                            :class="qty == preset ? 'bg-amber-500 text-slate-950 font-bold border-amber-500' : 'bg-slate-950 text-slate-300 border-slate-800'"
                                            class="py-2 rounded-xl border text-center transition">
                                        <span x-text="preset"></span> Vote
                                    </button>
                                </template>
                            </div>
                            <input type="number" name="vote_quantity" x-model="qty" required min="1" class="mt-2 w-full px-3 py-2 bg-slate-950 border border-slate-800 rounded-xl text-xs text-amber-400 font-mono font-bold">
                        </div>

                        {{-- Form Identitas Voter --}}
                        <div class="space-y-3">
                            <div>
                                <label class="block text-xs font-medium text-slate-400 mb-1">Nama Lengkap *</label>
                                <input type="text" name="voter_name" required placeholder="Nama Anda" class="w-full px-3 py-2 bg-slate-950 border border-slate-800 rounded-xl text-xs text-slate-100">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-400 mb-1">No. WhatsApp *</label>
                                <input type="tel" name="voter_phone" required placeholder="081234567890" class="w-full px-3 py-2 bg-slate-950 border border-slate-800 rounded-xl text-xs text-slate-100">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-400 mb-1">Pesan Dukungan (Opsional)</label>
                                <textarea name="support_message" rows="2" placeholder="Semangat menuju crown!" class="w-full px-3 py-2 bg-slate-950 border border-slate-800 rounded-xl text-xs text-slate-100"></textarea>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-400 mb-1">Metode Pembayaran</label>
                                <select name="payment_method" class="w-full px-3 py-2 bg-slate-950 border border-slate-800 rounded-xl text-xs text-amber-400 font-semibold">
                                    <option value="QRIS">QRIS (GoPay, OVO, ShopeePay, BCA)</option>
                                    <option value="VA_BCA">Virtual Account BCA</option>
                                    <option value="VA_MANDIRI">Virtual Account Mandiri</option>
                                </select>
                            </div>
                        </div>

                        {{-- Total Ringkasan --}}
                        <div class="p-3 bg-slate-950 rounded-xl border border-slate-800 flex justify-between items-center text-xs">
                            <span class="text-slate-400">Total Pembayaran:</span>
                            <span class="font-bold text-amber-400 text-sm font-mono" x-text="'Rp ' + (qty * price).toLocaleString('id-ID')"></span>
                        </div>

                        <button type="submit" class="w-full py-3 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold rounded-xl text-xs shadow-lg transition">
                            Lanjut ke Pembayaran →
                        </button>
                    </form>
                </div>
            </div>
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