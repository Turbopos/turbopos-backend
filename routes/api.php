<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerTransportController;
use App\Http\Controllers\DistributorController;
use App\Http\Controllers\OpnameController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SalesTransactionController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['jwt.auth', 'verify.user.exists'])->group(function () {
    Route::get('/profile', [AuthController::class, 'getProfile']);
    Route::post('/profile', [AuthController::class, 'update']);

    Route::resource('/category', CategoryController::class, ['except' => ['show', 'create']]);
    Route::resource('/user', UserController::class, ['except' => ['create']]);
    Route::resource('/customer', CustomerController::class, ['except' => ['create']]);
    Route::resource('/customer-transport', CustomerTransportController::class, ['except' => 'create']);
    Route::resource('/distributor', DistributorController::class, ['except' => ['create']]);
    Route::resource('/product', ProductController::class, ['except' => ['create']]);
    Route::resource('/purchase-order', PurchaseOrderController::class, ['except' => ['create', 'edit']]);
    Route::put('/purchase-order/{id}/status', [PurchaseOrderController::class, 'updateStatus']);
    Route::resource('/opname', OpnameController::class, ['except' => ['create', 'edit', 'update', 'destroy']]);
    Route::resource('/sales-transaction', SalesTransactionController::class, ['expect' => ['create', 'edit']]);
    Route::put('/sales-transaction/{id}/status', [SalesTransactionController::class, 'updateStatus']);

    Route::prefix('/report')->group(function () {
        Route::get('/dashboard', [ReportController::class, 'dashboardSummary']);
        Route::get('/purchase-order', [ReportController::class, 'purchaseOrderReport']);
        Route::get('/sales-transaction', [ReportController::class, 'salesTransactionReport']);
        Route::get('/profit-loss-item', [ReportController::class, 'profitLossItem']);
        Route::get('/profit-loss-category', [ReportController::class, 'profitLossCategory']);
        Route::get('/stock', [ReportController::class, 'stockReport']);
    });
});

Route::prefix('/setting')->group(function () {
    Route::get('/', [SettingController::class, 'index']);
    Route::middleware('jwt.auth', 'verify.user.exists')->put('/', [SettingController::class, 'update']);
});
