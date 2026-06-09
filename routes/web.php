<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

// Redirect root ke login
Route::get('/', fn() => redirect()->route('login'));

// Rute Profil
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
// Pastikan bagian ->name('dashboard') sudah ada
Route::get('/dashboard', [AdminController::class, 'superAdminDashboard'])->name('dashboard');
    // Fitur Manajemen User
    Route::post('/admin/user/{id}/jabatan', [AdminController::class, 'updateJabatan'])->name('admin.user.update.jabatan');
    Route::post('/admin/user/{id}/role', [AdminController::class, 'role'])->name('admin.user.role');
    Route::post('/admin/user/{id}/toggle', [AdminController::class, 'toggle'])->name('admin.user.toggle');
    Route::delete('/admin/user/{id}', [AdminController::class, 'delete'])->name('admin.user.delete');
    Route::get('/admin/approve/{id}', [RegisteredUserController::class, 'approveUser'])->name('admin.approve');

    // Fitur Barang
    Route::get('/barang', [ItemController::class, 'index'])->name('barang.index');
    Route::post('/barang', [ItemController::class, 'store'])->name('barang.store');
});

// --- RUTE ROLE: STAFF (Umum) ---
Route::middleware(['auth'])->group(function () {
    // FIX: Mengarahkan langsung ke dashboard admin yang sudah ada agar tidak error
Route::get('/admin/admdash', [AdminController::class, 'dashboardTeman'])->name('admin.admdash');});

// Google Auth
Route::get('/auth/google', fn() => Socialite::driver('google')->redirect())->name('google.login');
Route::get('/auth/google/callback', function () {
    $googleUser = Socialite::driver('google')->user();
    $user = User::updateOrCreate(['email' => $googleUser->email], [
        'first_name' => $googleUser->user['given_name'] ?? $googleUser->name,
        'last_name' => $googleUser->user['family_name'] ?? '',
        'google_id' => $googleUser->id,
        'email_verified_at' => now(),
    ]);
    Auth::login($user);

    if ($user->role === 'superadmin') return redirect()->route('superadmin.dashboard');
    if ($user->role === 'admin') return redirect()->route('admin.dashboard');
    return redirect()->route('dashboard');
});
Route::get('/check-approval/{email}', [RegisteredUserController::class, 'checkApproval']);
// Rute Publik
Route::post('/verify-otp', [RegisteredUserController::class, 'verifyOtp'])->name('verify.otp');
Route::get('/admin/approve/{id}', [RegisteredUserController::class, 'approveUser'])->name('admin.approve');


require __DIR__.'/auth.php';
