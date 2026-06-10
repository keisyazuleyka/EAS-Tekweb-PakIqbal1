<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\StokController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\LaporanController;

// --- REDIRECT UTAMA ---
Route::get('/', fn() => redirect()->route('login'));


// --- RUTE PROFIL ---
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// --- RUTE ROLE: SUPER ADMIN ---
Route::middleware(['auth', 'role:superadmin'])->group(function () {
    Route::get('/superadmin/dashboard', [AdminController::class, 'superAdminDashboard'])->name('superadmin.dashboard');
});


// --- RUTE ROLE: ADMIN & SUPER ADMIN ---
Route::middleware(['auth', 'role:superadmin,admin'])->group(function () {
    // Dashboard
    Route::get('/admin/dashboard', [AdminController::class, 'dashboardTeman'])->name('dashboard');

    // Manajemen User
    Route::post('/admin/user/{id}/jabatan', [AdminController::class, 'updateJabatan'])->name('admin.user.update.jabatan');
    Route::post('/admin/user/{id}/role', [AdminController::class, 'role'])->name('admin.user.role');
    Route::post('/admin/user/{id}/toggle', [AdminController::class, 'toggle'])->name('admin.user.toggle');
    Route::delete('/admin/user/{id}', [AdminController::class, 'delete'])->name('admin.user.delete');
    Route::get('/admin/approve/{id}', [RegisteredUserController::class, 'approveUser'])->name('admin.approve');

    // Fitur Barang
    Route::get('/admin/barang', [ItemController::class, 'index'])->name('barang.index');
    Route::post('/admin/barang', [ItemController::class, 'store'])->name('barang.store');
    Route::delete('/admin/barang/{id}', [ItemController::class, 'destroy'])->name('barang.destroy');

    // Fitur Stok
    Route::get('/admin/stok', [StokController::class, 'index'])->name('stok.index');
    Route::post('/admin/stok', [StokController::class, 'store'])->name('stok.store');

    // Fitur Laporan (Pindah ke dalam grup middleware)
    Route::get('/admin/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/admin/laporan/export/{type}', [LaporanController::class, 'export'])->name('laporan.export');
});


// --- RUTE ROLE: STAFF ---
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/admdash', [AdminController::class, 'dashboardTeman'])->name('admin.admdash');
});


// --- GOOGLE AUTHENTICATION ---
Route::get('/auth/google', fn() => Socialite::driver('google')->redirect())->name('google.login');

Route::get('/auth/google/callback', function () {
    $googleUser = Socialite::driver('google')->user();

    $user = User::updateOrCreate(
        ['email' => $googleUser->email],
        [
            'first_name'        => $googleUser->user['given_name'] ?? $googleUser->name,
            'last_name'         => $googleUser->user['family_name'] ?? '',
            'google_id'         => $googleUser->id,
            'email_verified_at' => now(),
        ]
    );

    Auth::login($user);

    if ($user->role === 'superadmin') {
        return redirect()->route('superadmin.dashboard');
    }

    return redirect()->route('dashboard');
});


// --- LAIN-LAIN (APPROVAL & OTP) ---
Route::get('/check-approval/{email}', [RegisteredUserController::class, 'checkApproval']);
Route::post('/verify-otp', [RegisteredUserController::class, 'verifyOtp'])->name('verify.otp');

// Load default auth routes (Breeze/UI)
require __DIR__.'/auth.php';
