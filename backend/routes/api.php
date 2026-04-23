<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Đường dẫn Đăng ký
Route::post('/register', [AuthController::class, 'register']);

// Đường dẫn Xác thực mail (Dùng link này trong email Mailtrap)
Route::get('/verify/{token}', [AuthController::class, 'verify']);
Route::post('/login', [AuthController::class, 'login']);