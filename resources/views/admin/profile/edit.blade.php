@extends('layouts.admin')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    {{-- Header --}}
    <div class="space-y-1 border-b border-[#EBE7DF] pb-4">
        <span class="text-[10px] font-bold tracking-widest text-[#C85A32] uppercase">Keamanan Akun</span>
        <h1 class="text-2xl font-extrabold text-[#1A1D1A] tracking-tight">Pengaturan Profil & Kredensial</h1>
        <p class="text-xs text-[#78756E]">Perbarui nama, alamat email, atau kata sandi akses administrator Anda</p>
    </div>

    {{-- Session Alerts --}}
    @if (session('success'))
        <div class="p-4 bg-[#F0FDF4] border border-[#BBF7D0] rounded-xl text-emerald-800 text-xs font-semibold flex items-center gap-2">
            <span>✅</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div class="p-4 bg-[#FFF5F2] border border-[#FCD2C4] rounded-xl text-[#C85A32] text-xs font-medium space-y-1.5">
            <p class="font-extrabold text-sm">Gagal memperbarui profil:</p>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form Edit Profil --}}
    <form action="{{ route('admin.profile.update') }}" method="POST"
          class="bg-white border border-[#EBE7DF] rounded-xl p-6 sm:p-8 space-y-6 shadow-[0_2px_10px_rgba(0,0,0,0.02)]">
        @csrf
        @method('PUT')

        {{-- Section 1: Informasi Dasar --}}
        <div class="space-y-4">
            <h2 class="text-xs font-extrabold tracking-wider uppercase text-[#C85A32] border-b border-[#F4F1EA] pb-2">
                1. Informasi Dasar Administrator
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-1">
                    <label class="block text-xs font-bold text-[#1A1D1A]">Nama Lengkap *</label>
                    <input type="text" name="name" value="{{ old('name', $admin->name) }}" required
                           class="w-full px-3.5 py-2.5 bg-[#FBF9F5] border border-[#EBE7DF] rounded-lg text-xs text-[#1A1D1A] focus:outline-none focus:border-[#1A1D1A]">
                </div>

                <div class="space-y-1">
                    <label class="block text-xs font-bold text-[#1A1D1A]">Alamat Email Login *</label>
                    <input type="email" name="email" value="{{ old('email', $admin->email) }}" required
                           class="w-full px-3.5 py-2.5 bg-[#FBF9F5] border border-[#EBE7DF] rounded-lg text-xs text-[#1A1D1A] focus:outline-none focus:border-[#1A1D1A]">
                </div>
            </div>
        </div>

        {{-- Section 2: Ubah Password --}}
        <div class="space-y-4">
            <h2 class="text-xs font-extrabold tracking-wider uppercase text-[#C85A32] border-b border-[#F4F1EA] pb-2">
                2. Pembaruan Kata Sandi (Kosongkan jika tidak diubah)
            </h2>

            <div class="space-y-4">
                <div class="space-y-1">
                    <label class="block text-xs font-bold text-[#1A1D1A]">Kata Sandi Saat Ini</label>
                    <input type="password" name="current_password" placeholder="••••••••"
                           class="w-full px-3.5 py-2.5 bg-[#FBF9F5] border border-[#EBE7DF] rounded-lg text-xs text-[#1A1D1A] focus:outline-none focus:border-[#1A1D1A]">
                    <p class="text-[10px] text-[#78756E]">Wajib diisi hanya jika Anda ingin mengganti kata sandi baru.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-[#1A1D1A]">Kata Sandi Baru</label>
                        <input type="password" name="new_password" placeholder="••••••••"
                               class="w-full px-3.5 py-2.5 bg-[#FBF9F5] border border-[#EBE7DF] rounded-lg text-xs text-[#1A1D1A] focus:outline-none focus:border-[#1A1D1A]">
                    </div>

                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-[#1A1D1A]">Konfirmasi Kata Sandi Baru</label>
                        <input type="password" name="new_password_confirmation" placeholder="••••••••"
                               class="w-full px-3.5 py-2.5 bg-[#FBF9F5] border border-[#EBE7DF] rounded-lg text-xs text-[#1A1D1A] focus:outline-none focus:border-[#1A1D1A]">
                    </div>
                </div>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="flex items-center justify-end gap-3 pt-4 border-t border-[#EBE7DF]">
            <button type="submit"
                    class="px-5 py-2.5 bg-[#1A1D1A] hover:bg-[#C85A32] text-[#FBF9F5] font-bold text-xs rounded-lg transition-colors duration-200 shadow-sm">
                Perbarui Kredensial
            </button>
        </div>
    </form>
</div>
@endsection