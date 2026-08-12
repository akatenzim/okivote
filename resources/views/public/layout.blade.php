<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'OkiVote - Digital Voting Platform')</title>

    {{-- OpenGraph Metadata --}}
    @yield('og_meta')

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex flex-col font-sans">

    {{-- Navbar (Tanpa Auth Buttons) --}}
    <header class="sticky top-0 z-50 bg-slate-900/90 backdrop-blur-md border-b border-slate-800">
        <div class="max-w-md mx-auto px-4 py-3 flex items-center justify-between">
            <a href="{{ route('home') }}" class="font-black text-xl tracking-wider text-amber-500">
                OkiVote<span class="text-xs text-slate-400 font-normal ml-1">.com</span>
            </a>
            <div class="flex items-center gap-3 text-xs">
                <a href="{{ route('home') }}" class="text-slate-300 hover:text-amber-400 font-medium">Event</a>
                <a href="{{ route('public.register-event') }}" class="bg-amber-500/20 text-amber-400 border border-amber-500/30 px-3 py-1.5 rounded-full font-semibold hover:bg-amber-500/30">
                    Buat Event
                </a>
            </div>
        </div>
    </header>

    {{-- Main Mobile-First Wrapper --}}
    <main class="flex-1 w-full max-w-md mx-auto px-4 py-6">
        @yield('content')
    </main>

    {{-- Footer Publik --}}
    <footer class="border-t border-slate-900 bg-slate-950 py-6 text-center text-xs text-slate-500">
        <div class="max-w-md mx-auto px-4 space-y-2">
            <p>© {{ date('Y') }} OkiVote. All rights reserved.</p>
            <p>Platform Voting Digital Multi-Event Indonesia</p>
        </div>
    </footer>

</body>
</html>