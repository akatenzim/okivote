<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - OkiVote</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-900 text-slate-100 min-h-screen">
    <nav class="border-b border-slate-800 bg-slate-950 px-6 py-4 flex items-center justify-between">
        <span class="font-bold text-lg text-amber-500">OkiVote Admin Panel</span>
        <div class="flex items-center gap-4">
            <span class="text-sm text-slate-400">{{ Auth::guard('admin')->user()->name }}</span>
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="text-sm bg-red-500/20 text-red-400 px-3 py-1.5 rounded-md hover:bg-red-500/30 transition">
                    Logout
                </button>
            </form>
        </div>
    </nav>

    <main class="p-6 max-w-7xl mx-auto">
        <h1 class="text-2xl font-bold mb-4">Dashboard Overview</h1>
        <div class="p-4 bg-slate-800 border border-slate-700 rounded-lg">
            <p class="text-slate-300">Selamat datang di Admin Panel OkiVote. Modul Event & Kandidat siap dikembangkan di Sprint berikutnya!</p>
        </div>
    </main>
</body>
</html>