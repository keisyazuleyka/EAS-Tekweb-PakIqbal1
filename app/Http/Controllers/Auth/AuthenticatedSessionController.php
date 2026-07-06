<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use App\Models\User;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        // 1. Validasi Input
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        dd($user, Hash::check($request->password, $user?->password));

        // 2. Cek apakah user ada dan password benar
        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors(['email' => 'Email atau password salah.']);
        }

        // 3. Cek Status Approval (Semua user wajib di-ACC)
        if (!$user->is_approved) {
            return back()->withErrors(['email' => 'Akun belum di-ACC oleh Admin. Mohon tunggu.']);
        }

        // 4. Proses Login
        Auth::login($user);
        $request->session()->regenerate();

        // 5. LOGIKA PENGARAHAN BERDASARKAN EMAIL DAN ROLE

        // Prioritas: Jika domain email @mlogistix.id, langsung ke dashboard teman
        if (str_contains($user->email, '@mlogistix.id')) {
            return redirect()->intended('/admin/admdash');
        }

        // Jika superadmin (domain @slogistix.id) -> ke dashboard superadmin
        if ($user->role === 'superadmin') {
            return redirect()->intended('/superadmin/dashboard');
        }

        // Jika admin/staf biasa -> ke dashboard admin
        elseif ($user->role === 'admin') {
            return redirect()->intended('/admin/admdash');
        }

        // Default jika role tidak ditemukan
        return redirect()->intended('/dashboard');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
