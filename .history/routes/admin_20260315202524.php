<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;

Route::prefix('admin')->name('admin.')->group(function () {

    // Guest routes (not logged in as admin)
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [LoginController::class, 'login']);
    });

    // Authenticated admin routes
    Route::middleware('auth:admin')->group(function () {
        // Logout
        Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // ==================== User Management ====================
        Route::resource('users', App\Http\Controllers\Admin\UserController::class);
        Route::post('users/{user}/status/{status}', [App\Http\Controllers\Admin\UserController::class, 'toggleStatus'])->name('users.status');

        // ==================== Orders ====================
        Route::get('orders', [App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{order}', [App\Http\Controllers\Admin\OrderController::class, 'show'])->name('orders.show');

        // ==================== Products & Categories ====================
        Route::resource('products', App\Http\Controllers\Admin\ProductController::class);
        Route::resource('landing-products', App\Http\Controllers\Admin\LandingProductController::class);
        Route::resource('product-claims', App\Http\Controllers\Admin\ProductClaimController::class)->except(['create', 'store', 'destroy']);
        Route::resource('product-categories', App\Http\Controllers\Admin\ProductCategoryController::class);
        Route::resource('repurchase-products', App\Http\Controllers\Admin\RepurchaseProductController::class);

        // ==================== Packages & Ranks ====================
        Route::resource('packages', App\Http\Controllers\Admin\PackageController::class);
        Route::resource('ranks', App\Http\Controllers\Admin\RankController::class);

        // ==================== KYC & Withdrawals & Funding ====================
        Route::resource('kyc', App\Http\Controllers\Admin\KycController::class)->only(['index', 'show', 'update']);
        Route::resource('withdrawals', App\Http\Controllers\Admin\WithdrawalController::class)->only(['index', 'show', 'update']);
        Route::resource('funding-requests', App\Http\Controllers\Admin\FundingRequestController::class)->only(['index', 'show', 'update']);

        // ==================== Commissions ====================
        Route::resource('commissions', App\Http\Controllers\Admin\CommissionController::class)->only(['index', 'show']);

        // ==================== Announcements ====================
        Route::resource('announcements', App\Http\Controllers\Admin\AnnouncementController::class);

        // ==================== Locations ====================
        Route::resource('states', App\Http\Controllers\Admin\StateController::class);
        Route::resource('pickup-centers', App\Http\Controllers\Admin\PickupCenterController::class);

        // ==================== Payments ====================
        Route::resource('payments', App\Http\Controllers\Admin\PaymentController::class)->only(['index', 'show', 'update']);

        // ==================== Support ====================
        Route::resource('supports', App\Http\Controllers\Admin\SupportController::class);

        // ==================== Upgrades ====================
        Route::resource('upgrades', App\Http\Controllers\Admin\UpgradeController::class)->only(['index', 'show']);

        // ==================== Wallets & Transactions ====================
        Route::resource('wallets', App\Http\Controllers\Admin\WalletController::class)->only(['index', 'show']);
        Route::resource('wallet-transactions', App\Http\Controllers\Admin\WalletTransactionController::class)->only(['index', 'show']);

        // ==================== VTU Management ====================
        Route::prefix('vtu')->name('vtu.')->group(function () {
            Route::resource('providers', App\Http\Controllers\Admin\Vtu\ProviderController::class);
            Route::resource('plans', App\Http\Controllers\Admin\Vtu\PlanController::class);
            Route::resource('api-settings', App\Http\Controllers\Admin\Vtu\ApiSettingController::class);
            Route::resource('transactions', App\Http\Controllers\Admin\Vtu\TransactionController::class)->only(['index', 'show']);
        });
    });
    Route::resource('admins', App\Http\Controllers\Admin\AdminController::class);
});