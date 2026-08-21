@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    {{-- Header Section --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="space-y-1">
            <span class="text-[10px] font-bold tracking-widest text-[#C85A32] uppercase">Modul Keuangan</span>
            <h1 class="text-2xl font-extrabold text-[#1A1D1A] tracking-tight">Settlement Organizer</h1>
            <p class="text-xs text-[#78756E]">Pencairan dana hasil voting bersih ke Penyelenggara Event (EO)</p>
        </div>
        <a href="{{ route('admin.settlements.create') }}"
           class="inline-flex items-center justify-center px-4 py-2.5 bg-[#1A1D1A] hover:bg-[#C85A32] text-[#FBF9F5] font-bold text-xs rounded-lg transition-colors duration-200 shadow-sm">
            + Buat Settlement Baru
        </a>
    </div>

    {{-- Session Alert --}}
    @if(session('success'))
        <div class="p-4 bg-[#F0FDF4] border border-[#BBF7D0] rounded-xl text-emerald-800 text-xs font-semibold flex items-center gap-2">
            <span>✅</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- Tabel Data Settlement --}}
    <div class="bg-white border border-[#EBE7DF] rounded-xl overflow-hidden shadow-[0_2px_10px_rgba(0,0,0,0.02)]">
        <div class="overflow-x-auto no-scrollbar">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#FBF9F5] text-[#1A1D1A] font-bold uppercase border-b border-[#EBE7DF]">
                    <tr>
                        <th class="p-4">No. Settlement</th>
                        <th class="p-4">Event</th>
                        <th class="p-4">Omset Bruto</th>
                        <th class="p-4">Platform Fee</th>
                        <th class="p-4">Hak Organizer (Net)</th>
                        <th class="p-4">Tanggal Settle</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#EBE7DF]">
                    @forelse($settlements as $st)
                        <tr class="hover:bg-[#FBF9F5] transition-colors">
                            <td class="p-4 font-mono font-extrabold text-[#C85A32] whitespace-nowrap">
                                {{ $st->settlement_number }}
                            </td>
                            <td class="p-4 font-bold text-[#1A1D1A]">
                                {{ $st->event->name }}
                            </td>
                            <td class="p-4 font-mono font-bold text-[#1A1D1A] whitespace-nowrap">
                                Rp {{ number_format($st->gross_revenue, 0, ',', '.') }}
                            </td>
                            <td class="p-4 font-mono font-extrabold text-rose-700 whitespace-nowrap">
                                -Rp {{ number_format($st->platform_fee, 0, ',', '.') }}
                            </td>
                            <td class="p-4 font-mono font-extrabold text-emerald-700 whitespace-nowrap">
                                Rp {{ number_format($st->net_organizer_amount, 0, ',', '.') }}
                            </td>
                            <td class="p-4 text-[#78756E] whitespace-nowrap">
                                {{ $st->settled_at ? $st->settled_at->format('d M Y, H:i') : '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-[#78756E] space-y-1">
                                <p class="font-bold text-[#1A1D1A]">Belum Ada Riwayat Settlement</p>
                                <p class="text-xs">Klik "+ Buat Settlement Baru" untuk mencairkan hasil voting event.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection