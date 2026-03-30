<?php

use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\PageController;
use App\Http\Controllers\TrainingController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TripayController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\Guru\DashboardController as GuruDashboardController;
use App\Http\Controllers\Guru\TrainingController as GuruTrainingController;
use App\Http\Controllers\Guru\CertificateController;
use App\Http\Controllers\Guru\ProfileController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\TrainingController as AdminTrainingController;
use App\Http\Controllers\Admin\SessionController;
use App\Http\Controllers\Admin\ParticipantController;
use App\Http\Controllers\Admin\AttendanceController as AdminAttendanceController;
use App\Http\Controllers\Admin\FinanceController;
use App\Http\Controllers\Admin\CertTemplateController;
use App\Http\Controllers\Admin\SettingController;

// ========================================
// PUBLIC ROUTES
// ========================================
Route::get('/', [PageController::class, 'landing'])->name('landing');
Route::get('/pelatihan', [TrainingController::class, 'explore'])->name('pelatihan.explore');
Route::get('/pelatihan/{slug}', [TrainingController::class, 'show'])->name('pelatihan.show');

// ========================================
// AUTH - GOOGLE
// ========================================
Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('auth.google.callback');

// ========================================
// AUTH - STANDARD (Login / Register / Logout)
// ========================================
Route::middleware('guest')->group(function () {
    Route::get('/login', function () { return view('auth.login'); })->name('login');
    Route::post('/login', function (\Illuminate\Http\Request $request) {
        $request->validate(['email' => 'required|email', 'password' => 'required']);
        if (\Illuminate\Support\Facades\Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            $request->session()->regenerate();
            $user = auth()->user();
            return redirect($user->isAdmin() ? '/admin/dashboard' : '/guru/dashboard');
        }
        return back()->withErrors(['email' => 'Email atau password salah.'])->onlyInput('email');
    });

    Route::get('/register', function () { return view('auth.register'); })->name('register');
    Route::post('/register', function (\Illuminate\Http\Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:8',
            'phone' => 'nullable|string|max:20',
            'school' => 'nullable|string|max:255',
        ]);
        $user = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'phone' => $request->phone,
            'school' => $request->school,
            'role' => 'guru',
        ]);
        \Illuminate\Support\Facades\Auth::login($user);
        return redirect('/guru/dashboard');
    });
});

Route::post('/logout', function (\Illuminate\Http\Request $request) {
    \Illuminate\Support\Facades\Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->middleware('auth')->name('logout');

// ========================================
// GURU ROUTES (Authenticated)
// ========================================
Route::middleware(['auth', 'role:guru'])->prefix('guru')->name('guru.')->group(function () {
    Route::get('/dashboard', [GuruDashboardController::class, 'index'])->name('dashboard');
    Route::get('/pelatihan', [GuruTrainingController::class, 'index'])->name('pelatihan.index');
    Route::get('/pelatihan/{id}', [GuruTrainingController::class, 'show'])->name('pelatihan.show');
    Route::get('/pelatihan/{id}/undangan', [GuruTrainingController::class, 'downloadUndangan'])->name('pelatihan.undangan');
    Route::post('/pelatihan/{id}/daftar', [RegistrationController::class, 'store'])->name('pelatihan.daftar');
    Route::post('/presensi/{sessionId}', [AttendanceController::class, 'checkin'])->name('presensi.checkin');

    // Chat
    Route::get('/pelatihan/{id}/chat', [ChatController::class, 'index'])->name('pelatihan.chat');
    Route::post('/pelatihan/{id}/chat', [ChatController::class, 'store'])->name('pelatihan.chat.store');
    Route::get('/pelatihan/{id}/chat/fetch', [ChatController::class, 'fetch'])->name('pelatihan.chat.fetch');

    // Rating
    Route::post('/pelatihan/{id}/rating', [RatingController::class, 'store'])->name('pelatihan.rating');

    // Sertifikat
    Route::get('/sertifikat', [CertificateController::class, 'index'])->name('sertifikat.index');
    Route::get('/sertifikat/{id}/download', [CertificateController::class, 'download'])->name('sertifikat.download');

    // Profil
    Route::get('/profil', [ProfileController::class, 'edit'])->name('profil.edit');
    Route::put('/profil', [ProfileController::class, 'update'])->name('profil.update');
});

// ========================================
// PEMBAYARAN (Authenticated)
// ========================================
Route::middleware('auth')->group(function () {
    Route::get('/pembayaran/{id}', [PaymentController::class, 'checkout'])->name('pembayaran.checkout');
    Route::post('/pembayaran/{id}/create', [PaymentController::class, 'create'])->name('pembayaran.create');
    Route::get('/pembayaran/{id}/status', [PaymentController::class, 'status'])->name('pembayaran.status');
});

// ========================================
// TRIPAY CALLBACK (No CSRF)
// ========================================
Route::post('/tripay/callback', [TripayController::class, 'callback'])
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class])
    ->name('tripay.callback');

// ========================================
// ADMIN ROUTES
// ========================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/pelatihan/{id}/rekap-peserta', [AdminTrainingController::class, 'downloadRekapPeserta'])->name('pelatihan.rekap-peserta');
    Route::resource('/pelatihan', AdminTrainingController::class);

    // Sessions
    Route::get('/pelatihan/{id}/jadwal', [SessionController::class, 'index'])->name('jadwal.index');
    Route::post('/pelatihan/{id}/jadwal', [SessionController::class, 'store'])->name('jadwal.store');
    Route::put('/jadwal/{sessionId}', [SessionController::class, 'update'])->name('jadwal.update');
    Route::delete('/jadwal/{sessionId}', [SessionController::class, 'destroy'])->name('jadwal.destroy');

    // Peserta & Presensi
    Route::get('/peserta', [ParticipantController::class, 'index'])->name('peserta.index');
    Route::get('/presensi', [AdminAttendanceController::class, 'index'])->name('presensi.index');
    Route::get('/presensi/export', [AdminAttendanceController::class, 'export'])->name('presensi.export');

    // Keuangan
    Route::get('/keuangan', [FinanceController::class, 'index'])->name('keuangan.index');
    Route::post('/keuangan/{id}/approve', [FinanceController::class, 'approve'])->name('keuangan.approve');
    // Sertifikat Template
    Route::resource('/sertifikat-template', CertTemplateController::class)->names('cert-template');
    Route::post('/sertifikat-template/{id}/preview', [CertTemplateController::class, 'preview'])->name('cert-template.preview');

    // Pengaturan
    Route::get('/pengaturan', [SettingController::class, 'index'])->name('pengaturan.index');
});
