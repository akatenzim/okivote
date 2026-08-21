@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    {{-- Header Section --}}
    <div class="space-y-1">
        <span class="text-[10px] font-bold tracking-widest text-[#C85A32] uppercase">Rekapitulasi Keuangan</span>
        <h1 class="text-2xl font-extrabold text-[#1A1D1A] tracking-tight">Riwayat Transaksi Voting</h1>
        <p class="text-xs text-[#78756E]">Pantau seluruh aktivitas masuk transaksi pembayaran secara real-time</p>
    </div>

    {{-- Form Filter --}}
    <form action="{{ route('admin.transactions.index') }}" method="GET"
          class="bg-white p-4 rounded-xl border border-[#EBE7DF] flex flex-wrap items-center gap-3 text-xs shadow-[0_2px_8px_rgba(0,0,0,0.02)]">

        <div class="flex-1 min-w-[220px]">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari Invoice / Nama Voter / No. HP..."
                   class="w-full px-3.5 py-2.5 bg-[#FBF9F5] border border-[#EBE7DF] rounded-lg text-xs text-[#1A1D1A] focus:outline-none focus:border-[#1A1D1A]">
        </div>

        <div>
            <select name="event_id" class="px-3.5 py-2.5 bg-[#FBF9F5] border border-[#EBE7DF] rounded-lg text-xs text-[#1A1D1A] font-bold focus:outline-none focus:border-[#1A1D1A]">
                <option value="">Semua Event</option>
                @foreach($events as $event)
                    <option value="{{ $event->id }}" {{ request('event_id') == $event->id ? 'selected' : '' }}>
                        {{ $event->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <select name="status" class="px-3.5 py-2.5 bg-[#FBF9F5] border border-[#EBE7DF] rounded-lg text-xs text-[#1A1D1A] font-bold focus:outline-none focus:border-[#1A1D1A]">
                <option value="">Semua Status</option>
                <option value="PENDING" {{ request('status') == 'PENDING' ? 'selected' : '' }}>PENDING</option>
                <option value="PAID" {{ request('status') == 'PAID' ? 'selected' : '' }}>PAID</option>
                <option value="EXPIRED" {{ request('status') == 'EXPIRED' ? 'selected' : '' }}>EXPIRED</option>
            </select>
        </div>

        <button type="submit"
                class="px-5 py-2.5 bg-[#1A1D1A] hover:bg-[#C85A32] text-[#FBF9F5] font-bold rounded-lg text-xs transition-colors duration-200 shadow-sm">
            Filter Data
        </button>

        @if(request()->anyFilled(['search', 'event_id', 'status']))
            <a href="{{ route('admin.transactions.index') }}"
               class="px-3.5 py-2.5 bg-[#FBF9F5] hover:bg-[#EFECE6] text-[#78756E] hover:text-[#1A1D1A] font-bold rounded-lg text-xs border border-[#EBE7DF] transition-colors">
                Reset
            </a>
        @endif
    </form>

    {{-- Tabel Data Transaksi --}}
    <div class="bg-white border border-[#EBE7DF] rounded-xl overflow-hidden shadow-[0_2px_10px_rgba(0,0,0,0.02)]">
        <div class="overflow-x-auto no-scrollbar">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#FBF9F5] text-[#1A1D1A] font-bold uppercase border-b border-[#EBE7DF]">
                    <tr>
                        <th class="p-4">Invoice</th>
                        <th class="p-4">Event & Kandidat</th>
                        <th class="p-4">Identitas Voter</th>
                        <th class="p-4">Kuantitas</th>
                        <th class="p-4">Total Bayar</th>
                        <th class="p-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#EBE7DF]">
                    @forelse($transactions as $tx)
                        <tr class="hover:bg-[#FBF9F5] transition-colors">
                            <td class="p-4 font-mono font-extrabold text-[#C85A32] whitespace-nowrap">
                                {{ $tx->invoice_number }}
                            </td>
                            <td class="p-4">
                                <div class="font-bold text-[#1A1D1A]">{{ $tx->candidate->name }}</div>
                                <div class="text-[11px] text-[#78756E] font-medium">{{ $tx->event->name }}</div>
                            </td>
                            <td class="p-4">
                                <div class="font-bold text-[#1A1D1A]">{{ $tx->voter_name }}</div>
                                <div class="text-[11px] font-mono text-[#78756E]">{{ $tx->voter_phone }}</div>
                            </td>
                            <td class="p-4 font-mono font-extrabold text-[#1A1D1A] whitespace-nowrap">
                                +{{ number_format($tx->vote_quantity) }} Vote
                            </td>
                            <td class="p-4 font-mono font-extrabold text-[#1A1D1A] whitespace-nowrap">
                                Rp {{ number_format($tx->grand_total, 0, ',', '.') }}
                            </td>
                            <td class="p-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-md text-[10px] font-extrabold font-mono tracking-wide
                                    @if($tx->status == 'PAID') bg-emerald-50 text-emerald-700 border border-emerald-200
                                    @elseif($tx->status == 'PENDING') bg-amber-50 text-amber-700 border border-amber-200
                                    @else bg-neutral-100 text-neutral-600 border border-neutral-200 @endif">
                                    {{ $tx->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-[#78756E] space-y-1">
                                <p class="font-bold text-[#1A1D1A]">Tidak Ada Transaksi Ditemukan</p>
                                <p class="text-xs">Coba sesuaikan kata kunci atau filter pencarian Anda.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-[#EBE7DF] bg-[#FBF9F5]">
            {{ $transactions->links() }}
        </div>
    </div>
</div>
@endsection