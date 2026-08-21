@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    {{-- Header & Navigasi Kembali --}}
    <div class="flex items-center justify-between border-b border-[#EBE7DF] pb-4">
        <div class="space-y-1">
            <span class="text-[10px] font-bold tracking-widest text-[#C85A32] uppercase">Manajemen Event</span>
            <h1 class="text-2xl font-extrabold text-[#1A1D1A] tracking-tight">Edit Event: {{ $event->name }}</h1>
        </div>
        <a href="{{ route('admin.events.index') }}"
           class="text-xs font-bold text-[#78756E] hover:text-[#1A1D1A] bg-white border border-[#EBE7DF] px-3.5 py-2 rounded-lg transition-colors">
            ← Kembali ke Daftar
        </a>
    </div>

    {{-- Error Alerts --}}
    @if ($errors->any())
        <div class="p-4 bg-[#FFF5F2] border border-[#FCD2C4] rounded-xl text-[#C85A32] text-xs font-medium space-y-1.5">
            <p class="font-extrabold text-sm">Terdapat kesalahan pengisian form:</p>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form Edit Event --}}
    <form action="{{ route('admin.events.update', $event->id) }}" method="POST" enctype="multipart/form-data"
          class="bg-white border border-[#EBE7DF] rounded-xl p-6 sm:p-8 space-y-6 shadow-[0_2px_10px_rgba(0,0,0,0.02)]">
        @csrf
        @method('PUT')

        {{-- Section 1: Identitas & Harga Vote --}}
        <div class="space-y-4">
            <h2 class="text-xs font-extrabold tracking-wider uppercase text-[#C85A32] border-b border-[#F4F1EA] pb-2">
                1. Identitas & Tarif Voting
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-1">
                    <label class="block text-xs font-bold text-[#1A1D1A]">Nama Event *</label>
                    <input type="text" name="name" value="{{ old('name', $event->name) }}" required
                           placeholder="Contoh: Miss Hijab Lampung 2026"
                           class="w-full px-3.5 py-2.5 bg-[#FBF9F5] border border-[#EBE7DF] rounded-lg text-xs text-[#1A1D1A] focus:outline-none focus:border-[#1A1D1A]">
                </div>

                <div class="space-y-1">
                    <label class="block text-xs font-bold text-[#1A1D1A]">Harga 1 Vote (Rupiah Utuh) *</label>
                    <input type="number" name="vote_price" value="{{ old('vote_price', $event->vote_price) }}" required min="0"
                           placeholder="2500"
                           class="w-full px-3.5 py-2.5 bg-[#FBF9F5] border border-[#EBE7DF] rounded-lg text-xs text-[#1A1D1A] font-mono font-extrabold focus:outline-none focus:border-[#1A1D1A]">
                    <p class="text-[10px] text-[#78756E]">Sistem Audit Log otomatis mencatat jika terjadi perubahan harga vote</p>
                </div>
            </div>
        </div>

        {{-- Section 2: Penyelenggara --}}
        <div class="space-y-4">
            <h2 class="text-xs font-extrabold tracking-wider uppercase text-[#C85A32] border-b border-[#F4F1EA] pb-2">
                2. Penyelenggara (Event Organizer)
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-1">
                    <label class="block text-xs font-bold text-[#1A1D1A]">Nama Organizer (EO) *</label>
                    <input type="text" name="organizer_name" value="{{ old('organizer_name', $event->organizer_name) }}" required
                           placeholder="Contoh: Lampung Pageant Org"
                           class="w-full px-3.5 py-2.5 bg-[#FBF9F5] border border-[#EBE7DF] rounded-lg text-xs text-[#1A1D1A] focus:outline-none focus:border-[#1A1D1A]">
                </div>

                <div class="space-y-1">
                    <label class="block text-xs font-bold text-[#1A1D1A]">Kontak Organizer (Internal) *</label>
                    <input type="text" name="organizer_contact" value="{{ old('organizer_contact', $event->organizer_contact) }}" required
                           placeholder="081234567890"
                           class="w-full px-3.5 py-2.5 bg-[#FBF9F5] border border-[#EBE7DF] rounded-lg text-xs text-[#1A1D1A] focus:outline-none focus:border-[#1A1D1A]">
                </div>
            </div>
        </div>

        {{-- Section 3: Jadwal & Status --}}
        <div class="space-y-4">
            <h2 class="text-xs font-extrabold tracking-wider uppercase text-[#C85A32] border-b border-[#F4F1EA] pb-2">
                3. Jadwal Periode & Status
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-1">
                    <label class="block text-xs font-bold text-[#1A1D1A]">Waktu Voting Mulai *</label>
                    <input type="datetime-local" name="voting_start_at"
                           value="{{ old('voting_start_at', $event->voting_start_at ? $event->voting_start_at->format('Y-m-d\TH:i') : '') }}" required
                           class="w-full px-3.5 py-2.5 bg-[#FBF9F5] border border-[#EBE7DF] rounded-lg text-xs text-[#1A1D1A] focus:outline-none focus:border-[#1A1D1A]">
                </div>

                <div class="space-y-1">
                    <label class="block text-xs font-bold text-[#1A1D1A]">Waktu Voting Selesai *</label>
                    <input type="datetime-local" name="voting_end_at"
                           value="{{ old('voting_end_at', $event->voting_end_at ? $event->voting_end_at->format('Y-m-d\TH:i') : '') }}" required
                           class="w-full px-3.5 py-2.5 bg-[#FBF9F5] border border-[#EBE7DF] rounded-lg text-xs text-[#1A1D1A] focus:outline-none focus:border-[#1A1D1A]">
                </div>

                <div class="space-y-1">
                    <label class="block text-xs font-bold text-[#1A1D1A]">Status Event *</label>
                    <select name="status" class="w-full px-3.5 py-2.5 bg-[#FBF9F5] border border-[#EBE7DF] rounded-lg text-xs text-[#1A1D1A] font-bold focus:outline-none focus:border-[#1A1D1A]">
                        <option value="DRAFT" {{ old('status', $event->status) == 'DRAFT' ? 'selected' : '' }}>DRAFT</option>
                        <option value="COMING_SOON" {{ old('status', $event->status) == 'COMING_SOON' ? 'selected' : '' }}>COMING_SOON</option>
                        <option value="ONGOING" {{ old('status', $event->status) == 'ONGOING' ? 'selected' : '' }}>ONGOING</option>
                        <option value="FINISHED" {{ old('status', $event->status) == 'FINISHED' ? 'selected' : '' }}>FINISHED</option>
                        <option value="SUSPENDED" {{ old('status', $event->status) == 'SUSPENDED' ? 'selected' : '' }}>SUSPENDED</option>
                    </select>
                </div>

                <div class="space-y-1">
                    <label class="block text-xs font-bold text-[#1A1D1A]">Ganti Poster Event (Opsional)</label>
                    <input type="file" name="poster" accept="image/*"
                           class="w-full px-3 py-2 bg-[#FBF9F5] border border-[#EBE7DF] rounded-lg text-xs text-[#78756E] focus:outline-none">
                    @if($event->poster_path)
                        <p class="text-[10px] text-[#78756E] mt-1">Poster saat ini: <span class="font-bold text-[#1A1D1A]">{{ $event->poster_path }}</span></p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Section 4: Deskripsi --}}
        <div class="space-y-2">
            <label class="block text-xs font-bold text-[#1A1D1A]">Deskripsi Event</label>
            <textarea name="description" rows="3"
                      placeholder="Tuliskan informasi ringkas atau aturan voting event..."
                      class="w-full px-3.5 py-2.5 bg-[#FBF9F5] border border-[#EBE7DF] rounded-lg text-xs text-[#1A1D1A] focus:outline-none focus:border-[#1A1D1A]">{{ old('description', $event->description) }}</textarea>
        </div>

        {{-- Form Action Buttons --}}
        <div class="flex items-center justify-end gap-3 pt-4 border-t border-[#EBE7DF]">
            <a href="{{ route('admin.events.index') }}"
               class="px-4 py-2.5 bg-[#FBF9F5] hover:bg-[#EFECE6] text-[#1A1D1A] font-bold text-xs rounded-lg border border-[#EBE7DF] transition-colors">
                Batal
            </a>
            <button type="submit"
                    class="px-5 py-2.5 bg-[#1A1D1A] hover:bg-[#C85A32] text-[#FBF9F5] font-bold text-xs rounded-lg transition-colors duration-200 shadow-sm">
                Perbarui Event
            </button>
        </div>
    </form>
</div>
@endsection