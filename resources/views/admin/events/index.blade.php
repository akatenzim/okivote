@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    {{-- Header Section --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="space-y-1">
            <span class="text-[10px] font-bold tracking-widest text-[#C85A32] uppercase">Manajemen Entitas</span>
            <h1 class="text-2xl font-extrabold text-[#1A1D1A] tracking-tight">Daftar Event Voting</h1>
            <p class="text-xs text-[#78756E]">Kelola periode, harga vote, dan status event kompetisi</p>
        </div>
        <a href="{{ route('admin.events.create') }}"
           class="inline-flex items-center justify-center px-4 py-2.5 bg-[#1A1D1A] hover:bg-[#C85A32] text-[#FBF9F5] font-bold text-xs rounded-lg transition-colors duration-200 shadow-sm">
            + Tambah Event Baru
        </a>
    </div>

    {{-- Notification Alert --}}
    @if (session('success'))
        <div class="p-4 bg-[#F0FDF4] border border-[#BBF7D0] rounded-xl text-emerald-800 text-xs font-semibold flex items-center gap-2">
            <span>✅</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- Table Container --}}
    <div class="bg-white border border-[#EBE7DF] rounded-xl overflow-hidden shadow-[0_2px_10px_rgba(0,0,0,0.02)]">
        <div class="overflow-x-auto no-scrollbar">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#FBF9F5] text-[#1A1D1A] font-bold uppercase border-b border-[#EBE7DF]">
                    <tr>
                        <th class="p-4">Event & Organizer</th>
                        <th class="p-4">Harga Vote</th>
                        <th class="p-4">Periode Voting</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#EBE7DF]">
                    @forelse ($events as $event)
                        <tr class="hover:bg-[#FBF9F5] transition-colors">
                            <td class="p-4">
                                <div class="font-extrabold text-sm text-[#1A1D1A]">{{ $event->name }}</div>
                                <div class="text-[11px] text-[#78756E] font-medium mt-0.5">
                                    EO: <span class="text-[#1A1D1A]">{{ $event->organizer_name }}</span> ({{ $event->organizer_contact }})
                                </div>
                            </td>
                            <td class="p-4 font-mono font-extrabold text-[#C85A32]">
                                Rp {{ number_format($event->vote_price, 0, ',', '.') }}
                            </td>
                            <td class="p-4 text-[11px] text-[#78756E] font-medium space-y-0.5">
                                <div><span class="font-bold text-[#1A1D1A]">Mulai:</span> {{ $event->voting_start_at ? $event->voting_start_at->format('d M Y, H:i') : '-' }}</div>
                                <div><span class="font-bold text-[#1A1D1A]">Selesai:</span> {{ $event->voting_end_at ? $event->voting_end_at->format('d M Y, H:i') : '-' }}</div>
                            </td>
                            <td class="p-4">
                                <span class="px-2.5 py-1 rounded-md text-[10px] font-extrabold font-mono tracking-wide
                                    @if($event->status == 'ONGOING') bg-emerald-50 text-emerald-700 border border-emerald-200
                                    @elseif($event->status == 'COMING_SOON') bg-blue-50 text-blue-700 border border-blue-200
                                    @elseif($event->status == 'FINISHED') bg-neutral-100 text-neutral-600 border border-neutral-200
                                    @else bg-amber-50 text-amber-700 border border-amber-200 @endif">
                                    {{ $event->status }}
                                </span>
                            </td>
                            <td class="p-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.events.edit', $event->id) }}"
                                       class="px-3 py-1.5 bg-[#FBF9F5] hover:bg-[#EFECE6] text-[#1A1D1A] font-bold rounded-lg border border-[#EBE7DF] text-[11px] transition-colors">
                                        Edit
                                    </a>
                                    <a href="{{ route('admin.candidates.index', ['event_id' => $event->id]) }}"
                                       class="px-3 py-1.5 bg-[#1A1D1A] hover:bg-[#C85A32] text-[#FBF9F5] font-bold rounded-lg text-[11px] transition-colors">
                                        Kandidat ({{ $event->candidates_count }})
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-[#78756E] space-y-1">
                                <p class="font-bold text-[#1A1D1A]">Belum Ada Event</p>
                                <p class="text-xs">Klik tombol "Tambah Event Baru" di atas untuk membuat kompetisi pertama.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-[#EBE7DF] bg-[#FBF9F5]">
            {{ $events->links() }}
        </div>
    </div>
</div>
@endsection