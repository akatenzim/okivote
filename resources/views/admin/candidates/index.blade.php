@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    {{-- Header Section --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="space-y-1">
            <span class="text-[10px] font-bold tracking-widest text-[#C85A32] uppercase">Manajemen Entitas</span>
            <h1 class="text-2xl font-extrabold text-[#1A1D1A] tracking-tight">Daftar Kandidat</h1>
            <p class="text-xs text-[#78756E]">Kelola peserta dan kandidat untuk seluruh event kompetisi</p>
        </div>
        <a href="{{ route('admin.candidates.create') }}"
           class="inline-flex items-center justify-center px-4 py-2.5 bg-[#1A1D1A] hover:bg-[#C85A32] text-[#FBF9F5] font-bold text-xs rounded-lg transition-colors duration-200 shadow-sm">
            + Tambah Kandidat Baru
        </a>
    </div>

    {{-- Session Alerts --}}
    @if (session('success'))
        <div class="p-4 bg-[#F0FDF4] border border-[#BBF7D0] rounded-xl text-emerald-800 text-xs font-semibold flex items-center gap-2">
            <span>✅</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="p-4 bg-[#FFF5F2] border border-[#FCD2C4] rounded-xl text-[#C85A32] text-xs font-semibold flex items-center gap-2">
            <span>⚠️</span>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- Table Container --}}
    <div class="bg-white border border-[#EBE7DF] rounded-xl overflow-hidden shadow-[0_2px_10px_rgba(0,0,0,0.02)]">
        <div class="overflow-x-auto no-scrollbar">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#FBF9F5] text-[#1A1D1A] font-bold uppercase border-b border-[#EBE7DF]">
                    <tr>
                        <th class="p-4">Foto & Nama</th>
                        <th class="p-4">No. Urut</th>
                        <th class="p-4">Event & Kategori</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#EBE7DF]">
                    @forelse ($candidates as $candidate)
                        <tr class="hover:bg-[#FBF9F5] transition-colors">
                            <td class="p-4 flex items-center gap-3">
                                <img src="{{ asset('storage/' . $candidate->profile_photo_path) }}"
                                     class="w-10 h-10 rounded-lg object-cover border border-[#EBE7DF] bg-[#EFECE6]">
                                <div>
                                    <div class="font-extrabold text-sm text-[#1A1D1A]">{{ $candidate->name }}</div>
                                    <div class="text-[11px] text-[#78756E] font-medium">{{ $candidate->region ?? '-' }}</div>
                                </div>
                            </td>
                            <td class="p-4 font-mono font-extrabold text-[#C85A32]">
                                #{{ $candidate->candidate_number }}
                            </td>
                            <td class="p-4">
                                <div class="font-bold text-[#1A1D1A]">{{ $candidate->event->name }}</div>
                                <div class="text-[11px] text-[#78756E]">{{ $candidate->category ? $candidate->category->name : 'Tanpa Kategori' }}</div>
                            </td>
                            <td class="p-4">
                                <span class="px-2.5 py-1 rounded-md text-[10px] font-extrabold font-mono tracking-wide
                                    {{ $candidate->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                                    {{ $candidate->is_active ? 'AKTIF' : 'NON-AKTIF' }}
                                </span>
                            </td>
                            <td class="p-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.candidates.edit', $candidate->id) }}"
                                       class="px-3 py-1.5 bg-[#FBF9F5] hover:bg-[#EFECE6] text-[#1A1D1A] font-bold rounded-lg border border-[#EBE7DF] text-[11px] transition-colors">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.candidates.destroy', $candidate->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kandidat ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1.5 bg-[#FFF5F2] hover:bg-[#C85A32] text-[#C85A32] hover:text-white border border-[#FCD2C4] hover:border-[#C85A32] font-bold rounded-lg text-[11px] transition-all">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-[#78756E] space-y-1">
                                <p class="font-bold text-[#1A1D1A]">Belum Ada Kandidat</p>
                                <p class="text-xs">Klik tombol "Tambah Kandidat Baru" untuk mendaftarkan peserta.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-[#EBE7DF] bg-[#FBF9F5]">
            {{ $candidates->links() }}
        </div>
    </div>
</div>
@endsection