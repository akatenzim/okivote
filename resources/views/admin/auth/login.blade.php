<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - OkiVote</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-900 text-slate-100 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-slate-800 border border-slate-700 rounded-xl p-6 shadow-2xl">
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-amber-500">OkiVote Admin</h1>
            <p class="text-sm text-slate-400">Silakan login untuk mengelola platform</p>
        </div>

        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-500/10 border border-red-500/50 rounded-lg text-red-400 text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('admin.login.post') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium mb-1">Email Admin</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                       class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-lg focus:outline-none focus:border-amber-500 text-sm">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Password</label>
                <input type="password" name="password" required
                       class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-lg focus:outline-none focus:border-amber-500 text-sm">
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2 text-sm text-slate-400 cursor-pointer">
                    <input type="checkbox" name="remember" class="rounded bg-slate-900 border-slate-700 text-amber-500 focus:ring-0">
                    Ingat Saya
                </label>
            </div>

            <button type="submit"
                    class="w-full py-2.5 px-4 bg-amber-500 hover:bg-amber-600 font-semibold text-slate-900 rounded-lg transition text-sm">
                Masuk ke Panel Admin
            </button>
        </form>
    </div>
</body>
</html>