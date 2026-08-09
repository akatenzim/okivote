<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Kandidat - OkiVote Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-900 text-slate-100 min-h-screen">
    <nav class="border-b border-slate-800 bg-slate-950 px-6 py-4 flex items-center justify-between">
        <div class="flex items-center gap-6">
            <span class="font-bold text-lg text-amber-500">OkiVote Admin</span>
            <a href="{{ route('admin.dashboard') }}" class="text-sm text-slate-400 hover:text-slate-200">Dashboard</a>
            <a href="{{ route('admin.events.index') }}" class="text-sm text-slate-400 hover:text-slate-200">Events</a>
            <a href="{{ route('admin.candidates.index') }}" class="text-sm text-amber-500 font-semibold">Candidates</a>
        </div>
        <form action="{{ route('admin.logout') }}" method="POST">
            @csrf
            <button type="submit" class="text-sm bg-red-500/20 text-red-400 px-3 py-1.5 rounded-md hover:bg-red-500/30">Logout</button>
        </form>
    </nav>

    <main class="p-6 max-w-7xl mx-auto space-y-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold">Daftar Kandidat</h1>
                <p class="text-sm text-slate-400">Kelola pesera / kandidat seluruh event</p>
            </div>
            <a href="{{ route('admin.candidates.create') }}" class="bg-amber-500 hover:bg-amber-600 text-slate-900 font-semibold px-4 py-2 rounded-lg text-sm transition">
                + Tambah Kandidat
            </a>
        </div>

        @if (session('success'))
            <div class="p-4 bg-emerald-500/10 border border-emerald-500/50 rounded-lg text-emerald-400 text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="p-4 bg-amber-500/10 border border-amber-500/50 rounded-lg text-amber-400 text-sm">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-slate-800 border border-slate-700 rounded-xl overflow-hidden">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-950 text-slate-400 uppercase text-xs border-b border-slate-700">
                    <tr>
                        <th class="p-4">Foto & Nama</th>
                        <th class="p-4">No. Urut</th>
                        <th class="p-4">Event & Kategori</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700">
                    @forelse ($candidates as $candidate)
                        <tr class="hover:bg-slate-700/50 transition">
                            <td class="p-4 flex items-center gap-3">
                                <img src="{{ asset('storage/' . $candidate->profile_photo_path) }}" class="w-10 h-10 rounded-full object-cover border border-slate-600">
                                <div>
                                    <div class="font-semibold text-slate-100">{{ $candidate->name }}</div>
                                    <div class="text-xs text-slate-400">{{ $candidate->region ?? '-' }}</div>
                                </div>
                            </td>
                            <td class="p-4 font-mono font-bold text-amber-400">
                                #{{ $candidate->candidate_number }}
                            </td>
                            <td class="p-4">
                                <div class="text-slate-200">{{ $candidate->event->name }}</div>
                                <div class="text-xs text-slate-400">{{ $candidate->category ? $candidate->category->name : 'Tanpa Kategori' }}</div>
                            </td>
                            <td class="p-4">
                                <span class="px-2 py-1 rounded text-xs font-semibold {{ $candidate->is_active ? 'bg-emerald-500/20 text-emerald-400' : 'bg-red-500/20 text-red-400' }}">
                                    {{ $candidate->is_active ? 'Aktif' : 'Non-Aktif' }}
                                </span>
                            </td>
                            <td class="p-4 text-center">
                                <form action="{{ route('admin.candidates.destroy', $candidate->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kandidat ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs bg-red-500/20 text-red-400 hover:bg-red-500/30 px-3 py-1.5 rounded">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-6 text-center text-slate-500">Belum ada kandidat yang diinput.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="p-4 border-t border-slate-700">
                {{ $candidates->links() }}
            </div>
        </div>
    </main>
</body>
</html>