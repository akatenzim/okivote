@extends('layouts.admin')

@section('content')
    <h1 class="text-2xl font-bold">Riwayat Transaksi Voting</h1>

    {{-- Form Filter --}}
    <form action="{{ route('admin.transactions.index') }}" method="GET" class="bg-slate-800 p-4 rounded-xl border border-slate-700 flex flex-wrap gap-3 text-xs">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Invoice / Nama / No HP..." class="px-3 py-2 bg-slate-900 border border-slate-700 rounded-lg text-slate-100 flex-1 min-w-[200px]">

        <select name="event_id" class="px-3 py-2 bg-slate-900 border border-slate-700 rounded-lg text-slate-100">
            <option value="">Semua Event</option>
            @foreach($events as $event)
                <option value="{{ $event->id }}" {{ request('event_id') == $event->id ? 'selected' : '' }}>{{ $event->name }}</option>
            @endforeach
        </select>

        <select name="status" class="px-3 py-2 bg-slate-900 border border-slate-700 rounded-lg text-slate-100">
            <option value="">Semua Status</option>
            <option value="PENDING" {{ request('status') == 'PENDING' ? 'selected' : '' }}>PENDING</option>
            <option value="PAID" {{ request('status') == 'PAID' ? 'selected' : '' }}>PAID</option>
            <option value="EXPIRED" {{ request('status') == 'EXPIRED' ? 'selected' : '' }}>EXPIRED</option>
        </select>

        <button type="submit" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold rounded-lg transition">Filter</button>
    </form>

    {{-- Tabel Data --}}
    <div class="bg-slate-800 border border-slate-700 rounded-xl overflow-hidden">
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-950 text-slate-400 uppercase border-b border-slate-700">
                <tr>
                    <th class="p-3">Invoice</th>
                    <th class="p-3">Event & Kandidat</th>
                    <th class="p-3">Voter</th>
                    <th class="p-3">Kuantitas</th>
                    <th class="p-3">Total Bayar</th>
                    <th class="p-3">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-700">
                @forelse($transactions as $tx)
                    <tr class="hover:bg-slate-700/50 transition">
                        <td class="p-3 font-mono text-amber-400 font-bold">{{ $tx->invoice_number }}</td>
                        <td class="p-3">
                            <div class="font-bold text-slate-200">{{ $tx->candidate->name }}</div>
                            <div class="text-[10px] text-slate-400">{{ $tx->event->name }}</div>
                        </td>
                        <td class="p-3">
                            <div>{{ $tx->voter_name }}</div>
                            <div class="text-[10px] text-slate-400">{{ $tx->voter_phone }}</div>
                        </td>
                        <td class="p-3 font-mono font-bold">+{{ number_format($tx->vote_quantity) }} Vote</td>
                        <td class="p-3 font-mono font-bold text-slate-100">Rp {{ number_format($tx->grand_total, 0, ',', '.') }}</td>
                        <td class="p-3">
                            <span class="px-2 py-0.5 rounded font-bold
                                @if($tx->status == 'PAID') bg-emerald-500/20 text-emerald-400 border border-emerald-500/30
                                @elseif($tx->status == 'PENDING') bg-amber-500/20 text-amber-400 border border-amber-500/30
                                @else bg-slate-500/20 text-slate-400 border border-slate-500/30 @endif">
                                {{ $tx->status }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-4 text-center text-slate-500">Tidak ada data transaksi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-3 border-t border-slate-700">{{ $transactions->links() }}</div>
    </div>
@endsection