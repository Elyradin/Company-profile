<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'apiRegister']);
    Route::post('/login', [AuthController::class, 'apiLogin']);
    
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'apiLogout']);
        Route::get('/user', function (Request $request) {
            return $request->user();
        });
        Route::put('/profile', [AuthController::class, 'apiUpdateProfile']);
        Route::put('/password', [AuthController::class, 'apiChangePassword']);
    });
});

