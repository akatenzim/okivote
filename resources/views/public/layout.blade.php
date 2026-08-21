<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'OkiVote — Platform Voting Transparan')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-[#FBF9F5] text-[#1A1D1A] antialiased min-h-screen flex flex-col selection:bg-[#C85A32] selection:text-white">

    <!-- Subtle Top Aesthetic Line -->
    <div class="h-1 w-full bg-gradient-to-r from-[#C85A32] via-[#D96B27] to-[#1A1D1A]"></div>

    <div class="w-full max-w-lg mx-auto min-h-screen flex flex-col px-4 sm:px-6">

        <!-- Header & Nav -->
        <header class="py-6 flex items-center justify-between border-b border-[#EBE7DF]">
            <a href="{{ route('home') }}" class="group flex items-center gap-2.5">
                <div class="w-9 h-9 bg-[#1A1D1A] text-[#FBF9F5] rounded-lg flex items-center justify-center font-black text-lg tracking-wider group-hover:bg-[#C85A32] transition-colors duration-200">
                    O
                </div>
                <div class="flex flex-col">
                    <span class="font-extrabold text-lg tracking-tight leading-none text-[#1A1D1A]">OKIVOTE<span class="text-[#C85A32]">.</span></span>
                    <span class="text-[9px] font-semibold tracking-widest text-[#78756E] uppercase mt-0.5">Official Voting</span>
                </div>
            </a>

            <a href="{{ route('public.register-event') }}" class="text-xs font-bold text-[#1A1D1A] bg-[#EFECE6] hover:bg-[#1A1D1A] hover:text-[#FBF9F5] px-3.5 py-2 rounded-md transition-all duration-200 border border-[#DCD7CD]">
                Buat Event
            </a>
        </header>

        <!-- Main Content -->
        <main class="flex-1 py-6 space-y-6">
            @yield('content')
        </main>

        <!-- Minimalist Editorial Footer -->
        <footer class="py-8 border-t border-[#EBE7DF] text-center space-y-2">
            <p class="text-xs font-bold tracking-wider uppercase text-[#1A1D1A]">OKIVOTE ENGINE</p>
            <p class="text-[11px] text-[#78756E]">Sistem Pemilihan Digital Transparan & Real-time</p>
        </footer>
    </div>

</body>
</html>