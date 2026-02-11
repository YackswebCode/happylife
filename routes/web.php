<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationCodeMail;

use App\Http\Controllers\LandingController;

// AUTH CONTROLLERS
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\VerifyController;
use App\Http\Controllers\Auth\PaymentController;

// MEMBER CONTROLLERS
use App\Http\Controllers\Member\DashboardController as MemberDashboardController;
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

/*
|--------------------------------------------------------------------------
| WEB ROUTES
|--------------------------------------------------------------------------
*/

// ================= LANDING PAGES =================
Route::get('/', [LandingController::class, 'index'])->name('home');
Route::get('/about', [LandingController::class, 'about'])->name('about');
Route::get('/packages', [LandingController::class, 'packages'])->name('packages');
Route::get('/contact', [LandingController::class, 'contact'])->name('contact');
Route::get('/faq', [LandingController::class, 'faq'])->name('faq');

// ================= AUTH ROUTES =================

// Register
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.post');

// AJAX endpoints for registration
Route::get('/check-username/{username}', [RegisterController::class, 'checkUsername']);
Route::get('/check-referral/{code}', [RegisterController::class, 'checkReferralCode']);

// Login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');

// Logout
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ================= TEST EMAIL (quick check) =================
Route::get('/test-email', function () {
    try {
        Mail::to('yahayaibraheem808@gmail.com')->send(new VerificationCodeMail(123456, 'Test User'));
        return 'Email sent successfully!';
    } catch (\Exception $e) {
        return 'Email failed: ' . $e->getMessage();
    }
});

// ================= EMAIL VERIFICATION =================
Route::get('/email/verify', [VerifyController::class, 'showVerifyForm'])->name('verification.notice');
Route::post('/email/verify', [VerifyController::class, 'verify'])->name('verification.verify');
Route::post('/email/verify/resend', [VerifyController::class, 'resend'])->name('verification.resend');

// ================= PAYMENT ROUTES (AFTER VERIFICATION) =================
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/payment', [PaymentController::class, 'showPayment'])->name('payment.show');
    Route::post('/payment/bank-transfer', [PaymentController::class, 'processBankTransfer'])->name('payment.bank-transfer');
    Route::post('/payment/activate', [PaymentController::class, 'activateUser'])->name('payment.activate');
});

// ================= MEMBER AREA – ONE SINGLE GROUP =================
Route::prefix('member')->name('member.')->middleware(['auth', 'verified'])->group(function () {

    // DASHBOARD
    Route::get('/dashboard', [MemberDashboardController::class, 'index'])->name('dashboard');

    // PROFILE
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/', [ProfileController::class, 'update'])->name('update');
    });

    // GENEALOGY
    Route::prefix('genealogy')->name('genealogy.')->group(function () {
        Route::get('/', [GenealogyController::class, 'index'])->name('index');
        Route::get('/load', [GenealogyController::class, 'loadChildren'])->name('load');
    });

    // ===== WALLET SYSTEM – FULLY DEFINED =====
    Route::prefix('wallet')->name('wallet.')->group(function () {
        Route::get('/', [WalletController::class, 'index'])->name('index');
        Route::get('/commission', [WalletController::class, 'commission'])->name('commission');
        Route::get('/rank', [WalletController::class, 'rank'])->name('rank');
        Route::get('/shopping', [WalletController::class, 'shopping'])->name('shopping');
        Route::get('/transactions', [WalletController::class, 'transactions'])->name('transactions');
        // Funding
        Route::get('/funding', [WalletController::class, 'funding'])->name('funding');
        Route::post('/pay/init', [WalletController::class, 'initPayment'])->name('pay.init');
        Route::get('/pay/verify', [WalletController::class, 'verifyPayment'])->name('verify-payment');
        Route::post('/request', [WalletController::class, 'requestFunding'])->name('request');
    });

    // SHOPPING MALL
    Route::get('/shopping', [ShoppingController::class, 'index'])->name('shopping');

    // VTU SERVICES
    Route::get('/vtu', [VTUController::class, 'index'])->name('vtu');

    // REFERRALS
    Route::get('/referrals', [ReferralController::class, 'index'])->name('referrals');

    // COMMISSIONS
    Route::get('/commissions', [CommissionController::class, 'index'])->name('commissions');

    // RANKS
    Route::prefix('ranks')->name('ranks.')->group(function () {
        Route::get('/', [RankController::class, 'index'])->name('index');
        Route::post('/claim', [RankController::class, 'claim'])->name('claim');
    });

    // WITHDRAW
    Route::prefix('withdraw')->name('withdraw.')->group(function () {
        Route::get('/', [WithdrawController::class, 'index'])->name('index');
        Route::post('/', [WithdrawController::class, 'store'])->name('store');
    });

    // SETTINGS
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingController::class, 'index'])->name('index');
        Route::put('/', [SettingController::class, 'update'])->name('update');
    });

    // ORDERS
    Route::get('/orders', [OrderController::class, 'index'])->name('orders');

    // CLAIM PRODUCT
    Route::prefix('claim-product')->name('claim-product.')->group(function () {
        Route::get('/', [ClaimProductController::class, 'index'])->name('index');
        Route::post('/', [ClaimProductController::class, 'store'])->name('store');
    });

    // KYC VERIFICATION
    Route::prefix('kyc')->name('kyc.')->group(function () {
        Route::get('/', [KycController::class, 'index'])->name('index');
        Route::post('/', [KycController::class, 'store'])->name('store');
        Route::put('/{kyc}', [KycController::class, 'update'])->name('update');
        Route::get('/document/{filename}', [KycController::class, 'viewDocument'])->name('document');
    });

});

// ================= REDIRECT /dashboard TO MEMBER DASHBOARD =================
Route::middleware(['auth', 'verified'])->get('/dashboard', function () {
    return redirect()->route('member.dashboard');
});