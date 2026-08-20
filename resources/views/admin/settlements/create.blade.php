@extends('layouts.admin')

@section('content')
    <h1 class="text-2xl font-bold">Proses Settlement Event</h1>

        @if(session('error'))
            <div class="p-4 bg-red-500/10 border border-red-500/50 rounded-lg text-red-400 text-sm">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('admin.settlements.store') }}" method="POST" class="bg-slate-800 p-6 rounded-xl border border-slate-700 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-medium text-slate-400 mb-1">Pilih Event *</label>
                <select name="event_id" required class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-lg text-xs text-slate-100">
                    @foreach($events as $event)
                        <option value="{{ $event->id }}">{{ $event->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-400 mb-1">Potongan Platform Fee (%) *</label>
                <input type="number" step="0.1" name="platform_fee_percent" value="15" required class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-lg text-xs font-mono font-bold text-amber-400">
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-400 mb-1">Catatan Tambahan</label>
                <textarea name="notes" rows="2" placeholder="Catatan transfer ke EO..." class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-lg text-xs text-slate-100"></textarea>
            </div>

            <button type="submit" class="w-full py-2.5 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold rounded-lg text-xs">
                Kalkulasi & Selesaikan Settlement
            </button>
        </form>
@endsection