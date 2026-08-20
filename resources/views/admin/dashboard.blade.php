@extends('layouts.admin')

@section('content')
    <h1 class="text-2xl font-bold">Ringkasan Performa Platform</h1>

    {{-- Metrics Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="p-4 bg-slate-800 border border-slate-700 rounded-xl space-y-1">
            <span class="text-xs text-slate-400">Total GMV (Bruto)</span>
            <div class="text-xl font-bold text-amber-400 font-mono">Rp {{ number_format($totalGmv, 0, ',', '.') }}</div>
        </div>
        <div class="p-4 bg-slate-800 border border-slate-700 rounded-xl space-y-1">
            <span class="text-xs text-slate-400">Total Suara Valid</span>
            <div class="text-xl font-bold text-slate-100 font-mono">{{ number_format($totalValidVotes) }} Vote</div>
        </div>
        <div class="p-4 bg-slate-800 border border-slate-700 rounded-xl space-y-1">
            <span class="text-xs text-slate-400">GMV Hari Ini</span>
            <div class="text-xl font-bold text-emerald-400 font-mono">Rp {{ number_format($gmvToday, 0, ',', '.') }}</div>
        </div>
        <div class="p-4 bg-slate-800 border border-slate-700 rounded-xl space-y-1">
            <span class="text-xs text-slate-400">Event Aktif</span>
            <div class="text-xl font-bold text-slate-100 font-mono">{{ $activeEvents }} / {{ $totalEvents }} Event</div>
        </div>
    </div>

    {{-- Tabel Transaksi Terbaru --}}
    <div class="bg-slate-800 border border-slate-700 rounded-xl overflow-hidden space-y-3 p-4">
        <h2 class="font-bold text-slate-200 text-sm">Transaksi Terbaru</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-950 text-slate-400 uppercase">
                    <tr>
                        <th class="p-3">Invoice</th>
                        <th class="p-3">Voter</th>
                        <th class="p-3">Kandidat</th>
                        <th class="p-3">Vote</th>
                        <th class="p-3">Total</th>
                        <th class="p-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700">
                    @forelse($recentTransactions as $tx)
                        <tr>
                            <td class="p-3 font-mono text-amber-400">{{ $tx->invoice_number }}</td>
                            <td class="p-3">{{ $tx->voter_name }} ({{ $tx->voter_phone }})</td>
                            <td class="p-3">{{ $tx->candidate->name }}</td>
                            <td class="p-3 font-mono font-bold">+{{ number_format($tx->vote_quantity) }}</td>
                            <td class="p-3 font-mono">Rp {{ number_format($tx->grand_total, 0, ',', '.') }}</td>
                            <td class="p-3">
                                <span class="px-2 py-0.5 rounded font-bold {{ $tx->status == 'PAID' ? 'bg-emerald-500/20 text-emerald-400' : 'bg-amber-500/20 text-amber-400' }}">
                                    {{ $tx->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="p-4 text-center text-slate-500">Belum ada transaksi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection