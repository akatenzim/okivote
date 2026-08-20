@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <h1 class="text-2xl font-bold">Audit Trail & Security Logs</h1>

    <div class="bg-slate-800 border border-slate-700 rounded-xl overflow-hidden">
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-950 text-slate-400 uppercase border-b border-slate-700">
                <tr>
                    <th class="p-3">Waktu</th>
                    <th class="p-3">Admin</th>
                    <th class="p-3">Aksi</th>
                    <th class="p-3">Nilai Lama</th>
                    <th class="p-3">Nilai Baru</th>
                    <th class="p-3">IP Address</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-700">
                @forelse($logs as $log)
                    <tr class="hover:bg-slate-700/50 transition">
                        <td class="p-3 text-slate-400 font-mono">{{ $log->created_at ? $log->created_at->format('d M Y, H:i:s') : '-' }}</td>
                        <td class="p-3 font-bold text-slate-200">{{ $log->admin->name ?? 'System' }}</td>
                        <td class="p-3">
                            <span class="px-2 py-0.5 rounded font-bold font-mono bg-amber-500/20 text-amber-400 border border-amber-500/30">
                                {{ $log->action }}
                            </span>
                        </td>
                        <td class="p-3 font-mono text-red-400">
                            {{ $log->old_values ? json_encode($log->old_values) : '-' }}
                        </td>
                        <td class="p-3 font-mono text-emerald-400">
                            {{ $log->new_values ? json_encode($log->new_values) : '-' }}
                        </td>
                        <td class="p-3 font-mono text-slate-400">{{ $log->ip_address }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="p-4 text-center text-slate-500">Belum ada catatan audit log.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-3 border-t border-slate-700">{{ $logs->links() }}</div>
    </div>
</div>
@endsection