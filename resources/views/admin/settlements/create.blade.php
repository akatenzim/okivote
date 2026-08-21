@extends('layouts.admin')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    {{-- Header & Navigasi Kembali --}}
    <div class="flex items-center justify-between border-b border-[#EBE7DF] pb-4">
        <div class="space-y-1">
            <span class="text-[10px] font-bold tracking-widest text-[#C85A32] uppercase">Kalkulasi Keuangan</span>
            <h1 class="text-2xl font-extrabold text-[#1A1D1A] tracking-tight">Proses Settlement Event</h1>
        </div>
        <a href="{{ route('admin.settlements.index') }}"
           class="text-xs font-bold text-[#78756E] hover:text-[#1A1D1A] bg-white border border-[#EBE7DF] px-3.5 py-2 rounded-lg transition-colors">
            ← Kembali ke Daftar
        </a>
    </div>

    {{-- Alert Error --}}
    @if(session('error'))
        <div class="p-4 bg-[#FFF5F2] border border-[#FCD2C4] rounded-xl text-[#C85A32] text-xs font-semibold flex items-center gap-2">
            <span>⚠️</span>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- Form Settlement --}}
    <form action="{{ route('admin.settlements.store') }}" method="POST"
          class="bg-white border border-[#EBE7DF] rounded-xl p-6 sm:p-8 space-y-5 shadow-[0_2px_10px_rgba(0,0,0,0.02)]">
        @csrf

        <div class="space-y-1">
            <label class="block text-xs font-bold text-[#1A1D1A]">Pilih Event Selesai *</label>
            <select name="event_id" required
                    class="w-full px-3.5 py-2.5 bg-[#FBF9F5] border border-[#EBE7DF] rounded-lg text-xs text-[#1A1D1A] font-bold focus:outline-none focus:border-[#1A1D1A]">
                @foreach($events as $event)
                    <option value="{{ $event->id }}">{{ $event->name }}</option>
                @endforeach
            </select>
            <p class="text-[10px] text-[#78756E]">Pastikan periode voting event telah habis atau ditutup.</p>
        </div>

        <div class="space-y-1">
            <label class="block text-xs font-bold text-[#1A1D1A]">Potongan Platform Fee (%) *</label>
            <input type="number" step="0.1" name="platform_fee_percent" value="15" required
                   placeholder="15"
                   class="w-full px-3.5 py-2.5 bg-[#FBF9F5] border border-[#EBE7DF] rounded-lg text-xs font-mono font-extrabold text-[#C85A32] focus:outline-none focus:border-[#1A1D1A]">
            <p class="text-[10px] text-[#78756E]">Persentase bagi hasil platform (default: 15%)</p>
        </div>

        <div class="space-y-1">
            <label class="block text-xs font-bold text-[#1A1D1A]">Catatan / Referensi Transfer (Opsional)</label>
            <textarea name="notes" rows="3"
                      placeholder="Contoh: Transfer via BCA a.n. Panitia Lampung Pageant No. Ref 882910..."
                      class="w-full px-3.5 py-2.5 bg-[#FBF9F5] border border-[#EBE7DF] rounded-lg text-xs text-[#1A1D1A] focus:outline-none focus:border-[#1A1D1A]"></textarea>
        </div>

        <div class="pt-4 border-t border-[#EBE7DF]">
            <button type="submit"
                    class="w-full py-3 bg-[#1A1D1A] hover:bg-[#C85A32] text-[#FBF9F5] font-bold rounded-lg text-xs transition-colors duration-200 shadow-sm">
                ⚡ Kalkulasi & Selesaikan Settlement
            </button>
        </div>
    </form>
</div>
@endsection