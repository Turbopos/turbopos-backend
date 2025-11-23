<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['jwt.auth', 'verify.user.exists'])->group(function () {
    Route::get('/profile', [AuthController::class, 'getProfile']);
});

