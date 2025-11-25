<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerTransportController;
use App\Http\Controllers\DistributorController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['jwt.auth', 'verify.user.exists'])->group(function () {
    Route::get('/profile', [AuthController::class, 'getProfile']);
    Route::resource('/category', CategoryController::class, ['except' => ['show', 'create']]);
    Route::resource('/user', UserController::class, ['except' => ['create']]);
    Route::resource('/customer', CustomerController::class, ['except' => ['create']]);
    Route::resource('/customer-transport', CustomerTransportController::class, ['except' => 'create']);
    Route::resource('/distributor', DistributorController::class, ['except' => ['create']]);
});

