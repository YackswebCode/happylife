<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;

Route::prefix('admin')->name('admin.')->group(function () {

    // Guest (not logged in as admin)
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [LoginController::class, 'login']);
    });

    // Authenticated admin routes
    Route::middleware('auth:admin')->group(function () {
        Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // User management
    Route::resource('users', App\Http\Controllers\Admin\UserController::class);
    Route::post('users/{user}/status/{status}', [App\Http\Controllers\Admin\UserController::class, 'toggleStatus'])->name('users.status');

Route::get('orders', [App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders.index');
Route::get('orders/{order}', [App\Http\Controllers\Admin\OrderController::class, 'show'])->name('orders.show');
        // Add more admin resource routes here later
    Route::resource('products', App\Http\Controllers\Admin\ProductController::class);
Route::resource('landing-products', App\Http\Controllers\Admin\LandingProductController::class);
Route::resource('product-claims', App\Http\Controllers\Admin\ProductClaimController::class)->except(['create', 'store', 'destroy']);
Route::resource('product-categories', App\Http\Controllers\Admin\ProductCategoryController::class);
Route::resource('repurchase-products', App\Http\Controllers\Admin\RepurchaseProductController::class);
Route::resource('packages', App\Http\Controllers\Admin\PackageController::class);
Route::resource('kyc', App\Http\Controllers\Admin\KycController::class)->only(['index', 'show', 'update']);
Route::resource('withdrawals', App\Http\Controllers\Admin\WithdrawalController::class)->only(['index', 'show', 'update']);
Route::resource('funding-requests', App\Http\Controllers\Admin\FundingRequestController::class)->only(['index', 'show', 'update']);   
Route::resource('commissions', App\Http\Controllers\Admin\CommissionController::class)->only(['index', 'show']);
Route::resource('announcements', App\Http\Controllers\Admin\AnnouncementController::class);
Route::resource('states', App\Http\Controllers\Admin\StateController::class);
Route::resource('pickup-centers', App\Http\Controllers\Admin\PickupCenterController::class);
Route::resource('payments', App\Http\Controllers\Admin\PaymentController::class)->only(['index', 'show', 'update']);
Route::resource('ranks', App\Http\Controllers\Admin\RankController::class);
});
});