<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ServicesController;
use App\Http\Middleware\EnsureAdmin;

// 1. AUTH (Login/Register - Mengeluarkan Token)
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'apiRegister']);
    Route::post('/login', [AuthController::class, 'apiLogin']); // Pastikan function ini return token

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'apiLogout']);
        Route::get('/user', function (Request $request) {
            return $request->user(); // Untuk cek siapa yang login
        });
        Route::put('/profile', [AuthController::class, 'apiUpdateProfile']);
        Route::put('/password', [AuthController::class, 'apiChangePassword']);
    });
});

// 2. ADMIN MANAGEMENT (Wajib Token + Admin)
Route::middleware(['auth:sanctum', EnsureAdmin::class])->prefix('admin')->group(function () {
    
    // USERS CRUD
    Route::apiResource('users', UserController::class);

    // SERVICES CRUD
    // Perhatikan: Karena ini API Resource, URL-nya otomatis:
    // GET /api/admin/services
    // POST /api/admin/services
    // PUT /api/admin/services/{id}
    // DELETE /api/admin/services/{id}
    Route::apiResource('services', ServicesController::class);
});

// 3. PUBLIC / MEMBER READ SERVICES (Wajib Token tapi User Biasa boleh)
Route::middleware(['auth:sanctum'])->group(function () {
    // Member hanya boleh baca (index & show)
    Route::get('/services', [ServicesController::class, 'index']);
});