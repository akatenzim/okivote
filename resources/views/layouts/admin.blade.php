<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Admin Panel' }} - OkiVote</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-900 text-slate-100 min-h-screen">

    {{-- Master Admin Navbar (Cukup Edit di File Ini!) --}}
    <nav class="border-b border-slate-800 bg-slate-950 px-6 py-4 flex items-center justify-between sticky top-0 z-50">
        <div class="flex items-center gap-6">
            <a href="{{ route('admin.dashboard') }}" class="font-bold text-lg text-amber-500 hover:text-amber-400">
                OkiVote <span class="text-xs text-slate-400 font-normal">Admin</span>
            </a>

            <div class="flex items-center gap-4 text-sm font-medium">
                <a href="{{ route('admin.dashboard') }}"
                class="{{ request()->routeIs('admin.dashboard') ? 'text-amber-500 font-semibold' : 'text-slate-400 hover:text-slate-200' }}">
                    Dashboard
                </a>
                <a href="{{ route('admin.events.index') }}"
                class="{{ request()->routeIs('admin.events.*') ? 'text-amber-500 font-semibold' : 'text-slate-400 hover:text-slate-200' }}">
                    Events
                </a>
                <a href="{{ route('admin.candidates.index') }}"
                class="{{ request()->routeIs('admin.candidates.*') ? 'text-amber-500 font-semibold' : 'text-slate-400 hover:text-slate-200' }}">
                    Candidates
                </a>
                <a href="{{ route('admin.transactions.index') }}"
                class="{{ request()->routeIs('admin.transactions.*') ? 'text-amber-500 font-semibold' : 'text-slate-400 hover:text-slate-200' }}">
                    Transactions
                </a>
                <a href="{{ route('admin.settlements.index') }}"
                class="{{ request()->routeIs('admin.settlements.*') ? 'text-amber-500 font-semibold' : 'text-slate-400 hover:text-slate-200' }}">
                    Settlements
                </a>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <span class="text-xs text-slate-400 hidden sm:inline">{{ Auth::guard('admin')->user()->name ?? 'Admin' }}</span>
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="text-xs bg-red-500/20 text-red-400 px-3 py-1.5 rounded-md hover:bg-red-500/30 transition">
                    Logout
                </button>
            </form>
        </div>
    </nav>

    {{-- Main Content Injector --}}
    <main class="p-6 max-w-7xl mx-auto space-y-6">
        @yield('content')
    </main>

</body>
</html>