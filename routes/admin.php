<?php

use Illuminate\Support\Facades\Route;

// Admin Auth & Dashboard
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;

// User Management
use App\Http\Controllers\Admin\UserController;

// Orders
use App\Http\Controllers\Admin\OrderController;

// Products & Categories
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\LandingProductController;
use App\Http\Controllers\Admin\ProductClaimController;
use App\Http\Controllers\Admin\ProductCategoryController;
use App\Http\Controllers\Admin\RepurchaseProductController;

// Packages & Ranks
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\RankController;

// KYC, Withdrawals & Funding
use App\Http\Controllers\Admin\KycController;
use App\Http\Controllers\Admin\WithdrawalController;
use App\Http\Controllers\Admin\FundingRequestController;

// Commissions & Announcements
use App\Http\Controllers\Admin\CommissionController;
use App\Http\Controllers\Admin\AnnouncementController;

// Locations
use App\Http\Controllers\Admin\StateController;
use App\Http\Controllers\Admin\PickupCenterController;

// Payments
use App\Http\Controllers\Admin\PaymentController;

// Support
use App\Http\Controllers\Admin\SupportController;

// Upgrades
use App\Http\Controllers\Admin\UpgradeController;

// Wallets & Transactions
use App\Http\Controllers\Admin\WalletController;
use App\Http\Controllers\Admin\WalletTransactionController;

// VTU Management
use App\Http\Controllers\Admin\Vtu\ProviderController;
use App\Http\Controllers\Admin\Vtu\PlanController;
use App\Http\Controllers\Admin\Vtu\ApiSettingController;
use App\Http\Controllers\Admin\Vtu\TransactionController;

// Admins & Profile
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ProfileController;

// Settings
use App\Http\Controllers\Admin\SettingController;

Route::prefix('admin')->name('admin.')->group(function () {

    // ==================== Guest Routes ====================
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [LoginController::class, 'login']);
    });

    // ==================== Authenticated Admin Routes ====================
    Route::middleware('auth:admin')->group(function () {

        // Logout
        Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // ==================== User Management ====================
        Route::resource('users', UserController::class);
        Route::post('users/{user}/status/{status}', [UserController::class, 'toggleStatus'])->name('users.status');

        // ==================== Orders ====================
        Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');

        // ==================== Products & Categories ====================
        Route::resource('products', ProductController::class);
        Route::resource('landing-products', LandingProductController::class);
        Route::resource('product-claims', ProductClaimController::class)->except(['create', 'store', 'destroy']);
        Route::resource('product-categories', ProductCategoryController::class);
        Route::resource('repurchase-products', RepurchaseProductController::class);

        // ==================== Packages & Ranks ====================
        Route::resource('packages', PackageController::class);
        Route::resource('ranks', RankController::class);

        // ==================== KYC, Withdrawals & Funding ====================
        Route::resource('kyc', KycController::class)->only(['index', 'show', 'update']);
        Route::resource('withdrawals', WithdrawalController::class)->only(['index', 'show', 'update']);
        Route::resource('funding-requests', FundingRequestController::class)->only(['index', 'show', 'update']);

        // ==================== Commissions ====================
        Route::resource('commissions', CommissionController::class)->only(['index', 'show']);

        // ==================== Announcements ====================
        Route::resource('announcements', AnnouncementController::class);

        // ==================== Locations ====================
        Route::resource('states', StateController::class);
        Route::resource('pickup-centers', PickupCenterController::class);

        // ==================== Payments ====================
        Route::resource('payments', PaymentController::class)->only(['index', 'show', 'update']);

        // ✅ Bank Settings Route
        Route::post('bank-settings', [PaymentController::class, 'updateBankSettings'])->name('bank-settings.update');

        // ==================== Support ====================
        Route::resource('supports', SupportController::class);

        // ==================== Upgrades ====================
        Route::resource('upgrades', UpgradeController::class)->only(['index', 'show']);

        // ==================== Wallets & Transactions ====================
        Route::resource('wallets', WalletController::class)->only(['index', 'show']);
        Route::resource('wallet-transactions', WalletTransactionController::class)->only(['index', 'show']);

        // ==================== VTU Management ====================
        Route::prefix('vtu')->name('vtu.')->group(function () {
            Route::resource('providers', ProviderController::class);
            Route::resource('plans', PlanController::class);
            Route::resource('api-settings', ApiSettingController::class);
            Route::resource('transactions', TransactionController::class)->only(['index', 'show']);
        });

        // ==================== Admins & Profile ====================
        Route::resource('admins', AdminController::class);
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

        // ==================== Settings ====================
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
    });

});