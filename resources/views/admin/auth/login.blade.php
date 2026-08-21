<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — OkiVote</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-[#FBF9F5] text-[#1A1D1A] antialiased min-h-screen flex items-center justify-center p-4 selection:bg-[#C85A32] selection:text-white">

    <div class="w-full max-w-sm space-y-6">

        {{-- Brand Header --}}
        <div class="text-center space-y-2">
            <div class="inline-flex items-center gap-2">
                <div class="w-9 h-9 bg-[#1A1D1A] text-[#FBF9F5] rounded-lg flex items-center justify-center font-black text-lg tracking-wider">
                    O
                </div>
                <span class="font-extrabold text-xl tracking-tight leading-none text-[#1A1D1A]">OKIVOTE<span class="text-[#C85A32]">.</span></span>
            </div>
            <p class="text-xs font-semibold text-[#78756E] uppercase tracking-widest pt-1">Portal Control Panel System</p>
        </div>

        {{-- Card Container --}}
        <div class="bg-white border border-[#EBE7DF] rounded-xl p-6 sm:p-8 space-y-5 shadow-[0_2px_12px_rgba(0,0,0,0.03)]">

            <div class="border-b border-[#EBE7DF] pb-3 text-center">
                <h1 class="text-base font-extrabold text-[#1A1D1A]">Autentikasi Administrator</h1>
            </div>

            {{-- Alert Error --}}
            @if ($errors->any())
                <div class="p-3 bg-[#FFF5F2] border border-[#FCD2C4] rounded-lg text-[#C85A32] text-xs font-medium space-y-1">
                    <p class="font-bold">Gagal Akses Masuk:</p>
                    <p>{{ $errors->first() }}</p>
                </div>
            @endif

            {{-- Form Login --}}
            <form action="{{ route('admin.login.post') }}" method="POST" class="space-y-4">
                @csrf

                <div class="space-y-1">
                    <label class="block text-xs font-bold text-[#1A1D1A]">Email Administrator *</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                           placeholder="admin@okivote.com"
                           class="w-full px-3.5 py-2.5 bg-[#FBF9F5] border border-[#EBE7DF] rounded-lg text-xs text-[#1A1D1A] focus:outline-none focus:border-[#1A1D1A] transition-colors">
                </div>

                <div class="space-y-1">
                    <label class="block text-xs font-bold text-[#1A1D1A]">Kata Sandi *</label>
                    <input type="password" name="password" required
                           placeholder="••••••••"
                           class="w-full px-3.5 py-2.5 bg-[#FBF9F5] border border-[#EBE7DF] rounded-lg text-xs text-[#1A1D1A] focus:outline-none focus:border-[#1A1D1A] transition-colors">
                </div>

                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center gap-2 text-xs text-[#78756E] cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded border-[#EBE7DF] text-[#1A1D1A] focus:ring-0">
                        Ingat Saya
                    </label>
                </div>

                <button type="submit"
                        class="w-full py-3 px-4 bg-[#1A1D1A] hover:bg-[#C85A32] text-[#FBF9F5] font-bold rounded-lg transition-colors duration-200 text-xs shadow-sm">
                    Masuk ke Control Panel →
                </button>
            </form>
        </div>

        {{-- Footer --}}
        <p class="text-center text-[10px] text-[#78756E]">
            &copy; {{ date('Y') }} OkiVote Engine. System Control & Security Logs Active.
        </p>
    </div>

</body>
</html>