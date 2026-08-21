@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    {{-- Header & Navigasi Kembali --}}
    <div class="flex items-center justify-between border-b border-[#EBE7DF] pb-4">
        <div class="space-y-1">
            <span class="text-[10px] font-bold tracking-widest text-[#C85A32] uppercase">Manajemen Entitas</span>
            <h1 class="text-2xl font-extrabold text-[#1A1D1A] tracking-tight">Edit Kandidat: {{ $candidate->name }}</h1>
        </div>
        <a href="{{ route('admin.candidates.index') }}"
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

    {{-- Form Edit Candidate --}}
    <form action="{{ route('admin.candidates.update', $candidate->id) }}" method="POST" enctype="multipart/form-data"
          class="bg-white border border-[#EBE7DF] rounded-xl p-6 sm:p-8 space-y-6 shadow-[0_2px_10px_rgba(0,0,0,0.02)]">
        @csrf
        @method('PUT')

        {{-- Section 1: Event & Nomor Urut --}}
        <div class="space-y-4">
            <h2 class="text-xs font-extrabold tracking-wider uppercase text-[#C85A32] border-b border-[#F4F1EA] pb-2">
                1. Penempatan Event & Urutan
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-1">
                    <label class="block text-xs font-bold text-[#1A1D1A]">Pilih Event *</label>
                    <select name="event_id" required
                            class="w-full px-3.5 py-2.5 bg-[#FBF9F5] border border-[#EBE7DF] rounded-lg text-xs text-[#1A1D1A] font-bold focus:outline-none focus:border-[#1A1D1A]">
                        @foreach ($events as $event)
                            <option value="{{ $event->id }}" {{ old('event_id', $candidate->event_id) == $event->id ? 'selected' : '' }}>
                                {{ $event->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-1">
                    <label class="block text-xs font-bold text-[#1A1D1A]">Nomor Urut Kandidat *</label>
                    <input type="text" name="candidate_number" value="{{ old('candidate_number', $candidate->candidate_number) }}" required
                           placeholder="Contoh: 01"
                           class="w-full px-3.5 py-2.5 bg-[#FBF9F5] border border-[#EBE7DF] rounded-lg text-xs text-[#1A1D1A] font-mono font-extrabold focus:outline-none focus:border-[#1A1D1A]">
                </div>
            </div>
        </div>

        {{-- Section 2: Profil & Status --}}
        <div class="space-y-4">
            <h2 class="text-xs font-extrabold tracking-wider uppercase text-[#C85A32] border-b border-[#F4F1EA] pb-2">
                2. Profil & Status Keaktifan
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-1">
                    <label class="block text-xs font-bold text-[#1A1D1A]">Nama Lengkap Kandidat *</label>
                    <input type="text" name="name" value="{{ old('name', $candidate->name) }}" required
                           placeholder="Nama lengkap kandidat"
                           class="w-full px-3.5 py-2.5 bg-[#FBF9F5] border border-[#EBE7DF] rounded-lg text-xs text-[#1A1D1A] focus:outline-none focus:border-[#1A1D1A]">
                </div>

                <div class="space-y-1">
                    <label class="block text-xs font-bold text-[#1A1D1A]">Asal / Region (Opsional)</label>
                    <input type="text" name="region" value="{{ old('region', $candidate->region) }}"
                           placeholder="Contoh: Bandar Lampung"
                           class="w-full px-3.5 py-2.5 bg-[#FBF9F5] border border-[#EBE7DF] rounded-lg text-xs text-[#1A1D1A] focus:outline-none focus:border-[#1A1D1A]">
                </div>

                <div class="space-y-1">
                    <label class="block text-xs font-bold text-[#1A1D1A]">Status Aktif Kandidat *</label>
                    <select name="is_active" class="w-full px-3.5 py-2.5 bg-[#FBF9F5] border border-[#EBE7DF] rounded-lg text-xs text-[#1A1D1A] font-bold focus:outline-none focus:border-[#1A1D1A]">
                        <option value="1" {{ old('is_active', $candidate->is_active) ? 'selected' : '' }}>AKTIF</option>
                        <option value="0" {{ !old('is_active', $candidate->is_active) ? 'selected' : '' }}>NON-AKTIF</option>
                    </select>
                </div>

                <div class="space-y-1">
                    <label class="block text-xs font-bold text-[#1A1D1A]">Ganti Foto Profil (Opsional)</label>
                    <input type="file" name="profile_photo" accept="image/*"
                           class="w-full px-3 py-2 bg-[#FBF9F5] border border-[#EBE7DF] rounded-lg text-xs text-[#78756E] focus:outline-none">
                </div>
            </div>
        </div>

        {{-- Section 3: Biografi --}}
        <div class="space-y-2">
            <label class="block text-xs font-bold text-[#1A1D1A]">Biografi / Deskripsi Singkat</label>
            <textarea name="biography" rows="3"
                      placeholder="Tuliskan latar belakang, prestasi, atau jargon kandidat..."
                      class="w-full px-3.5 py-2.5 bg-[#FBF9F5] border border-[#EBE7DF] rounded-lg text-xs text-[#1A1D1A] focus:outline-none focus:border-[#1A1D1A]">{{ old('biography', $candidate->biography) }}</textarea>
        </div>

        {{-- Action Buttons --}}
        <div class="flex items-center justify-end gap-3 pt-4 border-t border-[#EBE7DF]">
            <a href="{{ route('admin.candidates.index') }}"
               class="px-4 py-2.5 bg-[#FBF9F5] hover:bg-[#EFECE6] text-[#1A1D1A] font-bold text-xs rounded-lg border border-[#EBE7DF] transition-colors">
                Batal
            </a>
            <button type="submit"
                    class="px-5 py-2.5 bg-[#1A1D1A] hover:bg-[#C85A32] text-[#FBF9F5] font-bold text-xs rounded-lg transition-colors duration-200 shadow-sm">
                Perbarui Kandidat
            </button>
        </div>
    </form>
</div>
@endsection