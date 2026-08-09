<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Event Baru - OkiVote Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-900 text-slate-100 min-h-screen">
    <main class="p-6 max-w-4xl mx-auto my-8">
        <div class="mb-6 flex justify-between items-center">
            <h1 class="text-2xl font-bold">Buat Event Baru</h1>
            <a href="{{ route('admin.events.index') }}" class="text-sm text-slate-400 hover:text-slate-200">← Kembali</a>
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

        <form action="{{ route('admin.events.store') }}" method="POST" enctype="multipart/form-data" class="bg-slate-800 border border-slate-700 rounded-xl p-6 space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Nama Event *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Miss Hijab Lampung 2026"
                           class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-lg focus:outline-none focus:border-amber-500 text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Harga 1 Vote (IDR Rp) *</label>
                    <input type="number" name="vote_price" value="{{ old('vote_price', 2500) }}" required min="0" placeholder="2500"
                           class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-lg focus:outline-none focus:border-amber-500 text-sm font-mono">
                    <p class="text-xs text-slate-400 mt-1">Disimpan dalam Rupiah utuh (Integer)</p>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Nama Organizer (EO) *</label>
                    <input type="text" name="organizer_name" value="{{ old('organizer_name') }}" required placeholder="Contoh: Lampung Pageant Org"
                           class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-lg focus:outline-none focus:border-amber-500 text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Kontak Organizer (Internal) *</label>
                    <input type="text" name="organizer_contact" value="{{ old('organizer_contact') }}" required placeholder="081234567890"
                           class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-lg focus:outline-none focus:border-amber-500 text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Waktu Voting Mulai *</label>
                    <input type="datetime-local" name="voting_start_at" value="{{ old('voting_start_at') }}" required
                           class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-lg focus:outline-none focus:border-amber-500 text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Waktu Voting Selesai *</label>
                    <input type="datetime-local" name="voting_end_at" value="{{ old('voting_end_at') }}" required
                           class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-lg focus:outline-none focus:border-amber-500 text-sm">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Status Event *</label>
                    <select name="status" class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-lg focus:outline-none focus:border-amber-500 text-sm">
                        <option value="DRAFT">DRAFT</option>
                        <option value="COMING_SOON">COMING_SOON</option>
                        <option value="ONGOING" selected>ONGOING</option>
                        <option value="FINISHED">FINISHED</option>
                        <option value="SUSPENDED">SUSPENDED</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Poster Event (Opsional)</label>
                    <input type="file" name="poster" accept="image/*"
                           class="w-full px-3 py-1.5 bg-slate-900 border border-slate-700 rounded-lg focus:outline-none text-sm text-slate-400">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Deskripsi Event</label>
                <textarea name="description" rows="3" class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-lg focus:outline-none focus:border-amber-500 text-sm" placeholder="Tuliskan keterangan event..."></textarea>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-slate-700">
                <a href="{{ route('admin.events.index') }}" class="px-4 py-2 bg-slate-700 text-slate-300 rounded-lg text-sm hover:bg-slate-600">Batal</a>
                <button type="submit" class="px-5 py-2 bg-amber-500 hover:bg-amber-600 text-slate-900 font-semibold rounded-lg text-sm">Simpan Event</button>
            </div>
        </form>
    </main>
</body>
</html>