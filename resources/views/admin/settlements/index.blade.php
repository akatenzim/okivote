@extends('layouts.admin')

@section('content')
    <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold">Settlement Organizer</h1>
                <p class="text-sm text-slate-400">Pencairan dana hasil voting ke Penyelenggara Event</p>
            </div>
            <a href="{{ route('admin.settlements.create') }}" class="bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold px-4 py-2 rounded-lg text-sm">
                + Buat Settlement Baru
            </a>
        </div>

        @if(session('success'))
            <div class="p-4 bg-emerald-500/10 border border-emerald-500/50 rounded-lg text-emerald-400 text-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-slate-800 border border-slate-700 rounded-xl overflow-hidden">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-950 text-slate-400 uppercase border-b border-slate-700">
                    <tr>
                        <th class="p-3">No. Settlement</th>
                        <th class="p-3">Event</th>
                        <th class="p-3">Omset Bruto</th>
                        <th class="p-3">Platform Fee</th>
                        <th class="p-3">Hak Organizer (Net)</th>
                        <th class="p-3">Tanggal Settle</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700">
                    @forelse($settlements as $st)
                        <tr>
                            <td class="p-3 font-mono text-amber-400 font-bold">{{ $st->settlement_number }}</td>
                            <td class="p-3 font-bold text-slate-200">{{ $st->event->name }}</td>
                            <td class="p-3 font-mono">Rp {{ number_format($st->gross_revenue, 0, ',', '.') }}</td>
                            <td class="p-3 font-mono text-red-400">-Rp {{ number_format($st->platform_fee, 0, ',', '.') }}</td>
                            <td class="p-3 font-mono font-bold text-emerald-400">Rp {{ number_format($st->net_organizer_amount, 0, ',', '.') }}</td>
                            <td class="p-3 text-slate-400">{{ $st->settled_at ? $st->settled_at->format('d M Y, H:i') : '-' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="p-4 text-center text-slate-500">Belum ada riwayat settlement.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
@endsection