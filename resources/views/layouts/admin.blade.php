<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Control Panel' }} — OkiVote Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-[#FBF9F5] text-[#1A1D1A] antialiased min-h-screen flex flex-col selection:bg-[#C85A32] selection:text-white">

    {{-- Top Aesthetic Accent Line --}}
    <div class="h-1 w-full bg-gradient-to-r from-[#C85A32] via-[#D96B27] to-[#1A1D1A]"></div>

    {{-- Admin Navbar --}}
    <nav class="border-b border-[#EBE7DF] bg-white/90 backdrop-blur-md sticky top-0 z-50 px-4 sm:px-8 py-3.5 flex items-center justify-between shadow-[0_2px_10px_rgba(0,0,0,0.02)]">
        <div class="flex items-center gap-8">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 group">
                <div class="w-8 h-8 bg-[#1A1D1A] text-[#FBF9F5] rounded-lg flex items-center justify-center font-black text-base group-hover:bg-[#C85A32] transition-colors">
                    O
                </div>
                <div class="flex flex-col">
                    <span class="font-extrabold text-base tracking-tight leading-none text-[#1A1D1A]">OKIVOTE<span class="text-[#C85A32]">.</span></span>
                    <span class="text-[9px] font-bold tracking-widest text-[#78756E] uppercase">Control Panel</span>
                </div>
            </a>

            <div class="hidden md:flex items-center gap-1 text-xs font-bold">
                <a href="{{ route('admin.dashboard') }}"
                   class="px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-[#1A1D1A] text-[#FBF9F5]' : 'text-[#78756E] hover:text-[#1A1D1A] hover:bg-[#FBF9F5]' }}">
                    Dashboard
                </a>
                <a href="{{ route('admin.events.index') }}"
                   class="px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.events.*') ? 'bg-[#1A1D1A] text-[#FBF9F5]' : 'text-[#78756E] hover:text-[#1A1D1A] hover:bg-[#FBF9F5]' }}">
                    Event
                </a>
                <a href="{{ route('admin.candidates.index') }}"
                   class="px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.candidates.*') ? 'bg-[#1A1D1A] text-[#FBF9F5]' : 'text-[#78756E] hover:text-[#1A1D1A] hover:bg-[#FBF9F5]' }}">
                    Kandidat
                </a>
                <a href="{{ route('admin.transactions.index') }}"
                   class="px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.transactions.*') ? 'bg-[#1A1D1A] text-[#FBF9F5]' : 'text-[#78756E] hover:text-[#1A1D1A] hover:bg-[#FBF9F5]' }}">
                    Transaksi
                </a>
                <a href="{{ route('admin.settlements.index') }}"
                   class="px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.settlements.*') ? 'bg-[#1A1D1A] text-[#FBF9F5]' : 'text-[#78756E] hover:text-[#1A1D1A] hover:bg-[#FBF9F5]' }}">
                    Settlement
                </a>
                <a href="{{ route('admin.audit-logs.index') }}"
                   class="px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.audit-logs.*') ? 'bg-[#1A1D1A] text-[#FBF9F5]' : 'text-[#78756E] hover:text-[#1A1D1A] hover:bg-[#FBF9F5]' }}">
                    Audit Log
                </a>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <a href="{{ route('admin.profile.edit') }}"
            class="text-xs font-bold text-[#1A1D1A] hover:text-[#C85A32] bg-[#FBF9F5] hover:bg-[#EFECE6] border border-[#EBE7DF] px-3 py-1.5 rounded-lg transition-colors flex items-center gap-1.5">
                <span>👤</span>
                <span>{{ Auth::guard('admin')->user()->name ?? 'Administrator' }}</span>
            </a>
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="text-xs font-bold bg-[#FFF5F2] hover:bg-[#C85A32] text-[#C85A32] hover:text-white border border-[#FCD2C4] hover:border-[#C85A32] px-3.5 py-1.5 rounded-lg transition-all duration-200">
                    Keluar
                </button>
            </form>
        </div>
    </nav>

    {{-- Main Content Container --}}
    <main class="flex-1 p-4 sm:p-8 max-w-7xl w-full mx-auto space-y-6">
        @yield('content')
    </main>

    <footer class="py-6 border-t border-[#EBE7DF] text-center text-xs text-[#78756E] bg-white">
        &copy; {{ date('Y') }} OkiVote Platform Control System.
    </footer>

</body>
</html>