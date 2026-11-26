<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\PharmacyController;
use App\Http\Controllers\Pharmacy\MedicineController;
use App\Http\Controllers\Admin\PharmacyVerificationController;
use App\Http\Controllers\CustomerController;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register']);

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout']);

Route::get('/dashboard', function () {
    $role = auth()->user()->role;

    return match ($role) {
        'admin' => redirect('/admin/dashboard'),
        'pharmacy' => redirect('/pharmacy/dashboard'),
        'customer' => redirect('/customer/dashboard'),
        default => abort(403),
    };
})->middleware('auth');

Route::get('/admin/dashboard', function () {
    return view('admin.index');
})->middleware(['auth', 'role:admin']);

Route::middleware(['auth', 'role:pharmacy'])
    ->prefix('pharmacy')
    ->name('pharmacy.')
    ->group(function () {
        Route::get('/dashboard', [PharmacyController::class, 'index'])->name('dashboard');
        Route::get('/registration', [PharmacyController::class, 'create'])->name('registration');
        Route::post('/registration', [PharmacyController::class, 'store'])->name('registration.store');
        Route::get('/profile', [PharmacyController::class, 'profile'])->name('profile');
        Route::put('/profile', [PharmacyController::class, 'updateProfile'])->name('profile.update');

        Route::resource('medicines', MedicineController::class);

        Route::get('/stock-price', [PharmacyController::class, 'stock'])->name('stock');
        Route::put('/stock-price/{medicine}', [PharmacyController::class, 'updateStock'])->name('stock.update');

        Route::get('/requests', [PharmacyController::class, 'requests'])->name('requests');
        Route::post('/requests/{confirmationRequest}/respond', [PharmacyController::class, 'respondRequest'])->name('requests.respond');
    });

Route::middleware(['auth', 'role:customer'])
    ->prefix('customer')
    ->name('customer.')
    ->group(function () {
        Route::get('/dashboard', [CustomerController::class, 'dashboard'])->name('dashboard');
        Route::get('/medicines', [CustomerController::class, 'medicines'])->name('medicines');
        Route::get('/medicines/{id}', [CustomerController::class, 'showMedicine'])->name('medicines.show');
        Route::post('/medicines/{id}/request', [CustomerController::class, 'storeRequest'])->name('medicines.request');
        Route::get('/pharmacies', [CustomerController::class, 'pharmacies'])->name('pharmacies');
        Route::get('/requests', [CustomerController::class, 'requests'])->name('requests');
    });

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->group(function () {
        Route::resource('users', UserController::class)->except(['show']);
        Route::get('/pharmacies', [PharmacyVerificationController::class, 'index']);
        Route::get('/pharmacies/{pharmacy}', [PharmacyVerificationController::class, 'show']);
        Route::post('/pharmacies/{pharmacy}/approve', [PharmacyVerificationController::class, 'approve']);
        Route::post('/pharmacies/{pharmacy}/reject', [PharmacyVerificationController::class, 'reject']);
    });
