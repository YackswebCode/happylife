<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\VerifyController;
use App\Http\Controllers\Auth\PaymentController;
use App\Http\Controllers\Member\GenealogyController;
use App\Http\Controllers\Member\WalletController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ReferralController;
use App\Http\Controllers\Member\SupportController;
use App\Http\Controllers\Member\AnnouncementController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

// Landing pages
Route::get('/', [LandingController::class, 'index'])->name('home');
Route::get('/about', [LandingController::class, 'about'])->name('about');
Route::get('/contact', [LandingController::class, 'contact'])->name('contact');
Route::get('/faq', [LandingController::class, 'faq'])->name('faq');

// Authentication
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.post');
Route::get('/check-username/{username}', [RegisterController::class, 'checkUsername']);
Route::get('/check-referral/{code}', [RegisterController::class, 'checkReferralCode']);

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Email verification (requires auth but not verified)
Route::get('/email/verify', [VerifyController::class, 'showVerifyForm'])->name('verification.notice');
Route::post('/email/verify', [VerifyController::class, 'verify'])->name('verification.verify');
Route::post('/email/verify/resend', [VerifyController::class, 'resend'])->name('verification.resend');

// Payment (after auth + verification) – handled in member group? Actually it's after verification but before full member access.
// We'll keep it separate as it's a one-time step.
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/payment', [PaymentController::class, 'showPayment'])->name('payment.show');
    Route::post('/payment/bank-transfer', [PaymentController::class, 'processBankTransfer'])->name('payment.bank-transfer');
    Route::post('/payment/activate', [PaymentController::class, 'activateUser'])->name('payment.activate');
});

// Password reset (public)
Route::get('forgot-password', [ForgotPasswordController::class, 'showForgotForm'])->name('password.request');
Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetCode'])->name('password.email');
Route::get('verify-reset-code', [ForgotPasswordController::class, 'showVerifyCodeForm'])->name('password.verify.code');
Route::post('verify-reset-code', [ForgotPasswordController::class, 'verifyCode'])->name('password.verify.code.submit');
Route::get('reset-password', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset.form');
Route::post('reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.reset.submit');
Route::post('forgot-password/resend', [ForgotPasswordController::class, 'resendResetCode'])->name('password.resend');

// Public AJAX endpoints (no auth)
Route::get('/states/{country}', [LocationController::class, 'getStates']);
Route::get('/pickup-centers/{state}', [LocationController::class, 'getPickupCenters']);
Route::get('/check-referral/{code}', [ReferralController::class, 'check']);

// Redirect dashboard – after login/verified, go to member dashboard
Route::middleware(['auth', 'verified'])->get('/dashboard', function () {
    return redirect()->route('member.dashboard');
})->name('dashboard');

/*
|--------------------------------------------------------------------------
| MEMBER ROUTES (Authenticated & Verified)
|--------------------------------------------------------------------------
*/
Route::prefix('member')->name('member.')->middleware(['auth', 'verified'])->group(function () {

    // Dashboard (needs to be defined)
    Route::get('/dashboard', [App\Http\Controllers\Member\DashboardController::class, 'index'])->name('dashboard');

    // Genealogy
    Route::get('/genealogy', [GenealogyController::class, 'index'])->name('genealogy.index');

    // Matching Bonus
    Route::get('/matching-bonus', [App\Http\Controllers\Member\MatchingController::class, 'index'])->name('matching.index');

    // Support
    Route::get('/support', [SupportController::class, 'index'])->name('support.index');

    // Announcements
    Route::get('/announcements', [AnnouncementController::class, 'index'])->name('announcements.index');

    // Shopping
    Route::get('/shopping/receipt/{order}', [App\Http\Controllers\Member\ShoppingController::class, 'receipt'])->name('shopping.receipt');

    // Profile (with nested states AJAX)
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [App\Http\Controllers\Member\ProfileController::class, 'index'])->name('index');
        Route::get('/edit', [App\Http\Controllers\Member\ProfileController::class, 'edit'])->name('edit');
        Route::put('/update', [App\Http\Controllers\Member\ProfileController::class, 'update'])->name('update');
        Route::get('/states/{country}', [App\Http\Controllers\Member\ProfileController::class, 'getStates'])->name('states');
    });

    // Wallet routes
    Route::prefix('wallet')->name('wallet.')->group(function () {
        Route::get('/', [WalletController::class, 'index'])->name('index');
        Route::get('commission', [WalletController::class, 'commission'])->name('commission');
        Route::get('shopping', [WalletController::class, 'shopping'])->name('shopping');
        Route::get('transactions', [WalletController::class, 'transactions'])->name('transactions');
        Route::get('funding', [WalletController::class, 'funding'])->name('funding');
        Route::post('paystack/init', [WalletController::class, 'initPaystack'])->name('paystack.init');
        Route::post('flutterwave/init', [WalletController::class, 'initFlutterwave'])->name('flutterwave.init');
        Route::post('payment/success', [WalletController::class, 'paymentSuccess'])->name('payment.success');
        Route::post('request', [WalletController::class, 'requestFunding'])->name('request');
    });

    // VTU routes
    Route::prefix('vtu')->name('vtu.')->group(function () {
        Route::get('/', [App\Http\Controllers\Member\VTUController::class, 'index'])->name('index');
        Route::get('/airtime', [App\Http\Controllers\Member\VTUController::class, 'airtime'])->name('airtime');
        Route::post('/airtime/purchase', [App\Http\Controllers\Member\VTUController::class, 'purchaseAirtime'])->name('airtime.purchase');
        Route::get('/data', [App\Http\Controllers\Member\VTUController::class, 'data'])->name('data');
        Route::post('/data/purchase', [App\Http\Controllers\Member\VTUController::class, 'purchaseData'])->name('data.purchase');
        Route::get('/plans', [App\Http\Controllers\Member\VTUController::class, 'getPlans'])->name('plans'); // AJAX
            Route::get('/cable', [App\Http\Controllers\Member\VTUController::class, 'cable'])->name('cable');
    Route::post('/cable/purchase', [App\Http\Controllers\Member\VTUController::class, 'purchaseCable'])->name('cable.purchase');
    Route::get('/cable-plans', [App\Http\Controllers\Member\VTUController::class, 'getCablePlans'])->name('cable.plans');
    Route::post('/validate-smartcard', [App\Http\Controllers\Member\VTUController::class, 'validateSmartCard'])->name('validate.smartcard');
    });

    // Withdrawals
    Route::prefix('withdraw')->name('withdraw.')->group(function () {
        Route::get('/', [App\Http\Controllers\Member\WithdrawController::class, 'index'])->name('index');
        Route::post('/store', [App\Http\Controllers\Member\WithdrawController::class, 'store'])->name('store');
        Route::get('/history', [App\Http\Controllers\Member\WithdrawController::class, 'history'])->name('history');
        Route::post('/cancel/{id}', [App\Http\Controllers\Member\WithdrawController::class, 'cancel'])->name('cancel');
    });

    // KYC
    Route::get('/kyc', [App\Http\Controllers\Member\KycController::class, 'index'])->name('kyc.index');
    Route::post('/kyc/store', [App\Http\Controllers\Member\KycController::class, 'store'])->name('kyc.store');
});

/*
|--------------------------------------------------------------------------
| EXTERNAL ROUTE FILES
|--------------------------------------------------------------------------
*/
require __DIR__ . '/member.php';   // If you have additional member routes (ensure they don't conflict)
require __DIR__ . '/admin.php';