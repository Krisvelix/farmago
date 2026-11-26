<?php

use App\Http\Controllers\Api\MedicineController;
use Illuminate\Support\Facades\Route;

Route::get('/medicines', [MedicineController::class, 'index']);
Route::get('/pharmacies', [MedicineController::class, 'pharmacies']);
