<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\UserController;

// Guest auth routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/set-password/{token}', [UserController::class, 'showSetPasswordForm'])->name('password.set.form');
    Route::post('/set-password', [UserController::class, 'updateSetPassword'])->name('password.set.update');
});

// Email preview testing route
Route::get('/mail-preview/welcome', function () {
    $user = \App\Models\User::first() ?: new \App\Models\User([
        'name' => 'Domingo Isain Plaza Caamaño',
        'email' => 'domi@instalgaschile.cl',
        'role' => 'technician',
        'sec_code' => 'Gasfiter Certificado Autorizado SEC Clase 3',
        'rut' => '12.738.961-6',
    ]);
    $setupUrl = route('password.set.form', [
        'token' => 'token-demostracion-123456789',
        'email' => $user->email,
    ]);
    return new \App\Mail\TechnicianWelcomeMail($user, $setupUrl);
});

// Public certificate PDF view/download route (accessible by clients via shared link)
Route::get('/certificates/{certificate}/pdf', [CertificateController::class, 'downloadPdf'])->name('certificates.pdf');

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/', function () {
        return redirect()->route('certificates.index');
    });

    // Profile Management
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');

    // Certificate management (Static routes first)
    Route::get('/certificates', [CertificateController::class, 'index'])->name('certificates.index');
    Route::get('/certificates/create', [CertificateController::class, 'create'])->name('certificates.create');
    Route::get('/certificates/import', [CertificateController::class, 'showImportForm'])->name('certificates.import.form');
    Route::post('/certificates/import', [CertificateController::class, 'processImport'])->name('certificates.import.process');
    Route::post('/certificates', [CertificateController::class, 'store'])->name('certificates.store');

    // Parameter routes
    Route::get('/certificates/{certificate}', [CertificateController::class, 'show'])->name('certificates.show');
    Route::get('/certificates/{certificate}/edit', [CertificateController::class, 'edit'])->name('certificates.edit');
    Route::put('/certificates/{certificate}', [CertificateController::class, 'update'])->name('certificates.update');

    // Admin only routes
    Route::middleware(['App\Http\Middleware\RoleMiddleware:admin'])->group(function () {
        Route::delete('/certificates/{certificate}', [CertificateController::class, 'destroy'])->name('certificates.destroy');
        Route::post('/users/{user}/welcome-email', [UserController::class, 'sendWelcomeMail'])->name('users.welcome-email');
        Route::resource('users', UserController::class);
    });
});
