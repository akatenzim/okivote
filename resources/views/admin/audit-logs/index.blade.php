@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    {{-- Header Section --}}
    <div class="space-y-1">
        <span class="text-[10px] font-bold tracking-widest text-[#C85A32] uppercase">Keamanan & Log Akses</span>
        <h1 class="text-2xl font-extrabold text-[#1A1D1A] tracking-tight">Audit Trail & Security Logs</h1>
        <p class="text-xs text-[#78756E]">Catatan aktivitas sensitif administrator dan perubahan data sistem</p>
    </div>

    {{-- Tabel Audit Logs --}}
    <div class="bg-white border border-[#EBE7DF] rounded-xl overflow-hidden shadow-[0_2px_10px_rgba(0,0,0,0.02)]">
        <div class="overflow-x-auto no-scrollbar">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#FBF9F5] text-[#1A1D1A] font-bold uppercase border-b border-[#EBE7DF]">
                    <tr>
                        <th class="p-4">Waktu</th>
                        <th class="p-4">Admin</th>
                        <th class="p-4">Aksi</th>
                        <th class="p-4">Nilai Lama</th>
                        <th class="p-4">Nilai Baru</th>
                        <th class="p-4">IP Address</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#EBE7DF]">
                    @forelse($logs as $log)
                        <tr class="hover:bg-[#FBF9F5] transition-colors">
                            <td class="p-4 font-mono text-[#78756E] whitespace-nowrap">
                                {{ $log->created_at ? $log->created_at->format('d M Y, H:i:s') : '-' }}
                            </td>
                            <td class="p-4 font-extrabold text-[#1A1D1A] whitespace-nowrap">
                                {{ $log->admin->name ?? 'System / Engine' }}
                            </td>
                            <td class="p-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-md font-mono font-extrabold text-[10px] tracking-wide bg-[#FBF9F5] text-[#C85A32] border border-[#EBE7DF]">
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td class="p-4 font-mono text-xs text-rose-700 max-w-xs truncate">
                                {{ $log->old_values ? json_encode($log->old_values) : '-' }}
                            </td>
                            <td class="p-4 font-mono text-xs text-emerald-700 max-w-xs truncate">
                                {{ $log->new_values ? json_encode($log->new_values) : '-' }}
                            </td>
                            <td class="p-4 font-mono text-[#78756E] whitespace-nowrap">
                                {{ $log->ip_address }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-[#78756E] space-y-1">
                                <p class="font-bold text-[#1A1D1A]">Belum Ada Audit Log</p>
                                <p class="text-xs">Aktivitas sensitif admin akan otomatis tercatat di sini.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-[#EBE7DF] bg-[#FBF9F5]">
            {{ $logs->links() }}
        </div>
    </div>
</div>
@endsection