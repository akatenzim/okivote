<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Event - OkiVote Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-900 text-slate-100 min-h-screen">
    <nav class="border-b border-slate-800 bg-slate-950 px-6 py-4 flex items-center justify-between">
        <div class="flex items-center gap-6">
            <span class="font-bold text-lg text-amber-500">OkiVote Admin</span>
            <a href="{{ route('admin.dashboard') }}" class="text-sm text-slate-400 hover:text-slate-200">Dashboard</a>
            <a href="{{ route('admin.events.index') }}" class="text-sm text-amber-500 font-semibold">Events</a>
            <a href="{{ route('admin.candidates.index') }}" class="text-sm text-slate-400 hover:text-slate-200">Candidates</a>
        </div>
        <form action="{{ route('admin.logout') }}" method="POST">
            @csrf
            <button type="submit" class="text-sm bg-red-500/20 text-red-400 px-3 py-1.5 rounded-md hover:bg-red-500/30">Logout</button>
        </form>
    </nav>

    <main class="p-6 max-w-7xl mx-auto space-y-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold">Daftar Event Voting</h1>
                <p class="text-sm text-slate-400">Kelola periode, harga vote, dan status event</p>
            </div>
            <a href="{{ route('admin.events.create') }}" class="bg-amber-500 hover:bg-amber-600 text-slate-900 font-semibold px-4 py-2 rounded-lg text-sm transition">
                + Tambah Event
            </a>
        </div>

        @if (session('success'))
            <div class="p-4 bg-emerald-500/10 border border-emerald-500/50 rounded-lg text-emerald-400 text-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-slate-800 border border-slate-700 rounded-xl overflow-hidden">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-950 text-slate-400 uppercase text-xs border-b border-slate-700">
                    <tr>
                        <th class="p-4">Event & Organizer</th>
                        <th class="p-4">Harga Vote</th>
                        <th class="p-4">Periode Voting</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700">
                    @forelse ($events as $event)
                        <tr class="hover:bg-slate-700/50 transition">
                            <td class="p-4">
                                <div class="font-semibold text-slate-100">{{ $event->name }}</div>
                                <div class="text-xs text-slate-400">EO: {{ $event->organizer_name }} ({{ $event->organizer_contact }})</div>
                            </td>
                            <td class="p-4 font-mono text-amber-400">
                                Rp {{ number_format($event->vote_price, 0, ',', '.') }}
                            </td>
                            <td class="p-4 text-xs text-slate-300">
                                <div>Mulai: {{ $event->voting_start_at ? $event->voting_start_at->format('d M Y, H:i') : '-' }}</div>
                                <div>Selesai: {{ $event->voting_end_at ? $event->voting_end_at->format('d M Y, H:i') : '-' }}</div>
                            </td>
                            <td class="p-4">
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                                    @if($event->status == 'ONGOING') bg-emerald-500/20 text-emerald-400 border border-emerald-500/30
                                    @elseif($event->status == 'COMING_SOON') bg-blue-500/20 text-blue-400 border border-blue-500/30
                                    @elseif($event->status == 'FINISHED') bg-slate-500/20 text-slate-400 border border-slate-500/30
                                    @else bg-amber-500/20 text-amber-400 border border-amber-500/30 @endif">
                                    {{ $event->status }}
                                </span>
                            </td>
                            <td class="p-4 text-center space-x-2">
                                <a href="{{ route('admin.events.edit', $event->id) }}" class="text-xs bg-slate-700 hover:bg-slate-600 px-3 py-1.5 rounded text-slate-200">Edit</a>
                                <a href="{{ route('admin.candidates.index', ['event_id' => $event->id]) }}" class="text-xs bg-amber-500/20 text-amber-400 hover:bg-amber-500/30 px-3 py-1.5 rounded">Kandidat ({{ $event->candidates_count }})</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-6 text-center text-slate-500">Belum ada event yang dibuat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="p-4 border-t border-slate-700">
                {{ $events->links() }}
            </div>
        </div>
    </main>
</body>
</html>