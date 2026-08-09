<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kandidat - OkiVote Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-900 text-slate-100 min-h-screen">
    <main class="p-6 max-w-4xl mx-auto my-8">
        <div class="mb-6 flex justify-between items-center">
            <h1 class="text-2xl font-bold">Input Kandidat Baru</h1>
            <a href="{{ route('admin.candidates.index') }}" class="text-sm text-slate-400 hover:text-slate-200">← Kembali</a>
        </div>

        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-500/10 border border-red-500/50 rounded-lg text-red-400 text-sm">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.candidates.store') }}" method="POST" enctype="multipart/form-data" class="bg-slate-800 border border-slate-700 rounded-xl p-6 space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Pilih Event *</label>
                    <select name="event_id" required class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-lg focus:outline-none focus:border-amber-500 text-sm">
                        <option value="">-- Pilih Event --</option>
                        @foreach ($events as $event)
                            <option value="{{ $event->id }}" {{ old('event_id', $selectedEventId) == $event->id ? 'selected' : '' }}>
                                {{ $event->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Nomor Urut Kandidat *</label>
                    <input type="text" name="candidate_number" value="{{ old('candidate_number') }}" required placeholder="Contoh: 01"
                           class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-lg focus:outline-none focus:border-amber-500 text-sm font-mono">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Nama Lengkap Kandidat *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="Nama kandidat"
                           class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-lg focus:outline-none focus:border-amber-500 text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Asal / Region (Opsional)</label>
                    <input type="text" name="region" value="{{ old('region') }}" placeholder="Contoh: Bandar Lampung"
                           class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-lg focus:outline-none focus:border-amber-500 text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Foto Profil Kandidat *</label>
                    <input type="file" name="profile_photo" required accept="image/*"
                           class="w-full px-3 py-1.5 bg-slate-900 border border-slate-700 rounded-lg focus:outline-none text-sm text-slate-400">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">URL Media Sosial (Opsional)</label>
                    <input type="url" name="social_media_url" value="{{ old('social_media_url') }}" placeholder="https://instagram.com/username"
                           class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-lg focus:outline-none focus:border-amber-500 text-sm">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Biografi Singkat</label>
                <textarea name="biography" rows="3" class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-lg focus:outline-none focus:border-amber-500 text-sm" placeholder="Profil/prestasi kandidat..."></textarea>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-slate-700">
                <a href="{{ route('admin.candidates.index') }}" class="px-4 py-2 bg-slate-700 text-slate-300 rounded-lg text-sm hover:bg-slate-600">Batal</a>
                <button type="submit" class="px-5 py-2 bg-amber-500 hover:bg-amber-600 text-slate-900 font-semibold rounded-lg text-sm">Simpan Kandidat</button>
            </div>
        </form>
    </main>
</body>
</html>