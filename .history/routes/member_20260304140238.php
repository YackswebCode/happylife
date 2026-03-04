<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Member\DashboardController;
use App\Http\Controllers\Member\ProfileController;
use App\Http\Controllers\Member\GenealogyController;
use App\Http\Controllers\Member\WalletController;
use App\Http\Controllers\Member\ShoppingController;
use App\Http\Controllers\Member\VTUController;
use App\Http\Controllers\Member\ReferralController;
use App\Http\Controllers\Member\CommissionController;
use App\Http\Controllers\Member\RankController;
use App\Http\Controllers\Member\WithdrawController;
use App\Http\Controllers\Member\SettingController;
use App\Http\Controllers\Member\OrderController;
use App\Http\Controllers\Member\ClaimProductController;
use App\Http\Controllers\Member\KycController;
use App\Http\Controllers\Member\UpgradeController;

Route::prefix('member')->name('member.')->middleware(['auth', 'verified'])->group(function () {

    // DASHBOARD
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // PROFILE
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/', [ProfileController::class, 'update'])->name('update');
        Route::get('/states/{country}', [ProfileController::class, 'getStates'])->name('states');
    });

    // GENEALOGY
    Route::prefix('genealogy')->name('genealogy.')->group(function () {
        Route::get('/', [GenealogyController::class, 'index'])->name('index');
        Route::get('/load', [GenealogyController::class, 'loadChildren'])->name('load');
    });

    // WALLET
    Route::prefix('wallet')->name('wallet.')->group(function () {
        Route::get('/', [WalletController::class, 'index'])->name('index');
        Route::get('/commission', [WalletController::class, 'commission'])->name('commission');
        Route::get('/rank', [WalletController::class, 'rank'])->name('rank');
        Route::get('/shopping', [WalletController::class, 'shopping'])->name('shopping');
        Route::get('/transactions', [WalletController::class, 'transactions'])->name('transactions');
        Route::get('/funding', [WalletController::class, 'funding'])->name('funding');
        Route::post('/pay/init', [WalletController::class, 'initPayment'])->name('pay.init');
        Route::get('/pay/verify', [WalletController::class, 'verifyPayment'])->name('verify-payment');
        Route::post('/request', [WalletController::class, 'requestFunding'])->name('request');
    });

    // SHOPPING
    Route::prefix('shopping')->name('shopping.')->group(function () {
        Route::get('/', [ShoppingController::class, 'index'])->name('index');
        Route::get('/categories', [ShoppingController::class, 'categories'])->name('categories');
        Route::get('/product/{id}', [ShoppingController::class, 'show'])->name('product');
        Route::get('/cart', [ShoppingController::class, 'cart'])->name('cart');
        Route::post('/cart/add', [ShoppingController::class, 'addToCart'])->name('cart.add');
        Route::post('/cart/update', [ShoppingController::class, 'updateCart'])->name('cart.update');
        Route::post('/cart/remove', [ShoppingController::class, 'removeFromCart'])->name('cart.remove');
        Route::post('/checkout', [ShoppingController::class, 'checkout'])->name('checkout');
    });

   // VTU SERVICES
Route::prefix('vtu')->name('vtu.')->group(function () {
    Route::get('/', [VTUController::class, 'index'])->name('index');

    // Airtime
    Route::get('/airtime', [VTUController::class, 'airtime'])->name('airtime');
    Route::post('/airtime', [VTUController::class, 'purchaseAirtime'])->name('airtime.purchase');

    // Data
    Route::get('/data', [VTUController::class, 'data'])->name('data');
    Route::post('/data', [VTUController::class, 'purchaseData'])->name('data.purchase');
    Route::get('/plans', [VTUController::class, 'getPlans'])->name('plans');

    // Cable TV
    Route::get('/cable', [VTUController::class, 'cable'])->name('cable');
    Route::post('/cable', [VTUController::class, 'purchaseCable'])->name('cable.purchase');
    Route::get('/cable-plans', [VTUController::class, 'getCablePlans'])->name('cable.plans');
    Route::post('/validate-smartcard', [VTUController::class, 'validateSmartCard'])->name('validate.smartcard');

      Route::get('/electricity', [VTUController::class, 'electricity'])->name('electricity');
    Route::post('/electricity/validate', [VTUController::class, 'validateMeter'])->name('electricity.validate');
    Route::post('/electricity/purchase', [VTUController::class, 'purchaseElectricity'])->name('electricity.purchase');
});

    // REFERRALS
    Route::get('/referrals', [ReferralController::class, 'index'])->name('referrals');

    // COMMISSIONS
    Route::get('/commissions', [CommissionController::class, 'index'])->name('commissions');

    // RANKS
    Route::prefix('ranks')->name('ranks.')->group(function () {
        Route::get('/', [RankController::class, 'index'])->name('index');
        Route::post('/claim', [RankController::class, 'claim'])->name('claim');
    });

    // WITHDRAWALS
    Route::prefix('withdraw')->name('withdraw.')->group(function () {
        Route::get('/', [WithdrawController::class, 'index'])->name('index');
        Route::post('/', [WithdrawController::class, 'store'])->name('store');
        Route::get('/history', [WithdrawController::class, 'history'])->name('history');
        Route::delete('/cancel/{id}', [WithdrawController::class, 'cancel'])->name('cancel');
    });

    // SETTINGS
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingController::class, 'index'])->name('index');
        Route::put('/', [SettingController::class, 'update'])->name('update');
    });

    // ORDERS
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/{order}', [OrderController::class, 'show'])->name('show');
    });

    // CLAIM PRODUCT
    Route::prefix('claim-product')->name('claim-product.')->group(function () {
        Route::get('/', [ClaimProductController::class, 'index'])->name('index');
        Route::post('/', [ClaimProductController::class, 'store'])->name('store');
        Route::get('/receipt/{id}', [ClaimProductController::class, 'receipt'])->name('receipt');
        Route::delete('/cancel/{id}', [ClaimProductController::class, 'cancel'])->name('cancel');
    });

    // KYC
    Route::prefix('kyc')->name('kyc.')->group(function () {
        Route::get('/', [KycController::class, 'index'])->name('index');
        Route::post('/', [KycController::class, 'store'])->name('store');
        Route::put('/{kyc}', [KycController::class, 'update'])->name('update');
        Route::get('/document/{filename}', [KycController::class, 'viewDocument'])->name('document');
    });

    // UPGRADE PACKAGE
    Route::prefix('upgrade')->name('upgrade.')->group(function () {
        Route::get('/', [UpgradeController::class, 'index'])->name('index');
        Route::post('/', [UpgradeController::class, 'process'])->name('process');
    });
});