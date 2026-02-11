<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AdController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\PaymentController;

// مسیرهای عمومی
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/ads', [AdController::class, 'index']);
Route::get('/ads/{id}', [AdController::class, 'show']);

// مسیرهای نیازمند احراز هویت
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    
    // آگهی‌ها
    Route::post('/ads', [AdController::class, 'store']);
    Route::put('/ads/{id}', [AdController::class, 'update'])->middleware('ad.owner');
    Route::delete('/ads/{id}', [AdController::class, 'destroy'])->middleware('ad.owner');
    Route::post('/ads/{id}/promote', [AdController::class, 'promote']);
    
    // چت
    Route::get('/chat/rooms', [ChatController::class, 'rooms']);
    Route::post('/chat/rooms', [ChatController::class, 'createRoom']);
    Route::get('/chat/rooms/{id}/messages', [ChatController::class, 'messages']);
    Route::post('/chat/rooms/{id}/messages', [ChatController::class, 'sendMessage']);
    
    // پرداخت
    Route::post('/payments/online', [PaymentController::class, 'initiateOnline']);
    Route::post('/payments/crypto', [PaymentController::class, 'initiateCrypto']);
});

// مسیرهای مدیریت
Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {
    Route::get('/users', [\App\Http\Controllers\Api\Admin\UserController::class, 'index']);
    Route::put('/users/{id}/verify', [\App\Http\Controllers\Api\Admin\UserController::class, 'verify']);
    Route::get('/ads/pending', [\App\Http\Controllers\Api\Admin\AdController::class, 'pending']);
    Route::put('/ads/{id}/approve', [\App\Http\Controllers\Api\Admin\AdController::class, 'approve']);
    Route::put('/ads/{id}/reject', [\App\Http\Controllers\Api\Admin\AdController::class, 'reject']);
});