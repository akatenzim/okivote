<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AuditLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Tampilkan form profil admin
     */
    public function edit()
    {
        $admin = Auth::guard('admin')->user();
        return view('admin.profile.edit', compact('admin'));
    }

    /**
     * Perbarui informasi profil dan kata sandi
     */
    public function update(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:admins,email,' . $admin->id,
            'current_password' => 'nullable|required_with:new_password|string',
            'new_password' => ['nullable', 'confirmed', Password::defaults()],
        ]);

        $oldEmail = $admin->email;

        // 1. Verifikasi Password Saat Ini Jika Ingin Mengubah Password
        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $admin->password)) {
                return back()->withErrors(['current_password' => 'Kata sandi saat ini tidak cocok.']);
            }

            $admin->password = Hash::make($request->new_password);
        }

        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->save();

        // 2. Catat ke Audit Log jika ada perubahan sensitif
        AuditLogService::log(
            action: 'UPDATE_ADMIN_PROFILE',
            entityType: get_class($admin),
            entityId: $admin->id,
            oldValues: ['email' => $oldEmail],
            newValues: ['email' => $admin->email],
            reason: 'Pembaruan kredensial profil administrator'
        );

        return back()->with('success', 'Profil dan kredensial berhasil diperbarui!');
    }
}