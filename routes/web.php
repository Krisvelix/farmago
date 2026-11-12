
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\PharmacyController;
use App\Http\Controllers\Admin\PharmacyVerificationController;

// Halaman utama
Route::get('/', function () {
    return redirect('/login');
});

// Register
Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register']);

// Login
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Logout
Route::post('/logout', [AuthController::class, 'logout']);

// Dashboard
// Dashboard berdasarkan role
Route::get('/dashboard', function () {
    $role = auth()->user()->role;

    return match ($role) {
        'admin' => redirect('/admin/dashboard'),
        'pharmacy' => redirect('/pharmacy/dashboard'),
        'customer' => redirect('/customer/dashboard'),
        default => abort(403),
    };
})->middleware('auth');

// Dashboard Admin
Route::get('/admin/dashboard', function () {
    return view('admin.index');
})->middleware(['auth', 'role:admin']);

// Dashboard Apotek
Route::get('/pharmacy/dashboard', function () {
    return view('pharmacy.index');
})->middleware(['auth', 'role:pharmacy']);

// Dashboard Customer
Route::get('/customer/dashboard', function () {
    return view('customer.index');
})->middleware(['auth', 'role:customer']);

// CRUD Pengguna khusus Admin
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->group(function () {
        Route::resource('users', UserController::class)
            ->except(['show']);
    });

// Pendaftaran Apotek
Route::middleware(['auth', 'role:pharmacy'])->group(function () {
    Route::get('/pharmacy/registration', [PharmacyController::class, 'create']);

    Route::post('/pharmacy/registration', [PharmacyController::class, 'store']);
});

// Verifikasi Apotek khusus Admin
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->group(function () {

        Route::get('/pharmacies', [PharmacyVerificationController::class, 'index']);

        Route::get('/pharmacies/{pharmacy}', [PharmacyVerificationController::class, 'show']);

        Route::post('/pharmacies/{pharmacy}/approve', [PharmacyVerificationController::class, 'approve']);

        Route::post('/pharmacies/{pharmacy}/reject', [PharmacyVerificationController::class, 'reject']);

    });