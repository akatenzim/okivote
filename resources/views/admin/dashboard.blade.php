@extends('layouts.admin')

@section('content')
<div class="space-y-7">

    {{-- Header Title --}}
    <div class="space-y-1">
        <span class="text-[10px] font-bold tracking-widest text-[#C85A32] uppercase">Executive Overview</span>
        <h1 class="text-2xl font-extrabold text-[#1A1D1A] tracking-tight">Ringkasan Performa Platform</h1>
    </div>

    {{-- Metrics Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

        <div class="p-5 bg-white border border-[#EBE7DF] rounded-xl space-y-2 shadow-[0_2px_8px_rgba(0,0,0,0.02)]">
            <span class="text-xs font-bold text-[#78756E]">Total GMV (Bruto)</span>
            <div class="text-2xl font-extrabold text-[#1A1D1A] font-mono">
                Rp {{ number_format($totalGmv, 0, ',', '.') }}
            </div>
            <span class="text-[10px] font-semibold text-[#78756E] block">Akumulasi Transaksi PAID</span>
        </div>

        <div class="p-5 bg-white border border-[#EBE7DF] rounded-xl space-y-2 shadow-[0_2px_8px_rgba(0,0,0,0.02)]">
            <span class="text-xs font-bold text-[#78756E]">Total Suara Valid</span>
            <div class="text-2xl font-extrabold text-[#C85A32] font-mono">
                {{ number_format($totalValidVotes) }} <span class="text-xs text-[#1A1D1A]">Vote</span>
            </div>
            <span class="text-[10px] font-semibold text-[#78756E] block">Tercatat di Vote Ledger</span>
        </div>

        <div class="p-5 bg-white border border-[#EBE7DF] rounded-xl space-y-2 shadow-[0_2px_8px_rgba(0,0,0,0.02)]">
            <span class="text-xs font-bold text-[#78756E]">GMV Hari Ini</span>
            <div class="text-2xl font-extrabold text-emerald-700 font-mono">
                Rp {{ number_format($gmvToday, 0, ',', '.') }}
            </div>
            <span class="text-[10px] font-semibold text-[#78756E] block">Pencapaian {{ date('d M Y') }}</span>
        </div>

        <div class="p-5 bg-white border border-[#EBE7DF] rounded-xl space-y-2 shadow-[0_2px_8px_rgba(0,0,0,0.02)]">
            <span class="text-xs font-bold text-[#78756E]">Status Event</span>
            <div class="text-2xl font-extrabold text-[#1A1D1A] font-mono">
                {{ $activeEvents }} <span class="text-xs font-normal text-[#78756E]">/ {{ $totalEvents }} Aktif</span>
            </div>
            <span class="text-[10px] font-semibold text-[#78756E] block">Event Berlangsung</span>
        </div>

    </div>

    {{-- Tabel Transaksi Terbaru --}}
    <div class="bg-white border border-[#EBE7DF] rounded-xl overflow-hidden shadow-[0_2px_10px_rgba(0,0,0,0.02)] space-y-3 p-5">
        <div class="flex justify-between items-center border-b border-[#EBE7DF] pb-3">
            <div>
                <h2 class="font-extrabold text-base text-[#1A1D1A]">Transaksi Terbaru</h2>
                <p class="text-xs text-[#78756E]">Aktivitas masuk transaksi voting secara real-time</p>
            </div>
            <a href="{{ route('admin.transactions.index') }}" class="text-xs font-bold text-[#C85A32] hover:underline">
                Lihat Semua →
            </a>
        </div>

        <div class="overflow-x-auto no-scrollbar">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#FBF9F5] text-[#1A1D1A] font-bold uppercase border-b border-[#EBE7DF]">
                    <tr>
                        <th class="p-3.5">Invoice</th>
                        <th class="p-3.5">Voter</th>
                        <th class="p-3.5">Kandidat</th>
                        <th class="p-3.5">Vote</th>
                        <th class="p-3.5">Total Tagihan</th>
                        <th class="p-3.5">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#EBE7DF]">
                    @forelse($recentTransactions as $tx)
                        <tr class="hover:bg-[#FBF9F5] transition-colors">
                            <td class="p-3.5 font-mono font-extrabold text-[#C85A32]">{{ $tx->invoice_number }}</td>
                            <td class="p-3.5">
                                <div class="font-bold text-[#1A1D1A]">{{ $tx->voter_name }}</div>
                                <div class="text-[10px] text-[#78756E] font-mono">{{ $tx->voter_phone }}</div>
                            </td>
                            <td class="p-3.5 font-bold text-[#1A1D1A]">{{ $tx->candidate->name }}</td>
                            <td class="p-3.5 font-mono font-extrabold text-[#1A1D1A]">+{{ number_format($tx->vote_quantity) }}</td>
                            <td class="p-3.5 font-mono font-bold text-[#1A1D1A]">Rp {{ number_format($tx->grand_total, 0, ',', '.') }}</td>
                            <td class="p-3.5">
                                <span class="px-2.5 py-1 rounded-md text-[10px] font-extrabold font-mono tracking-wide
                                    {{ $tx->status == 'PAID' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                                    {{ $tx->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-6 text-center text-[#78756E]">Belum ada data transaksi tercatat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection