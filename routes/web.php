<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\VerifyController;
use App\Http\Controllers\Auth\PaymentController;
use App\Http\Controllers\Member\GenealogyController;
use App\Http\Controllers\Member\WalletController;

/*
|--------------------------------------------------------------------------
| LANDING PAGES
|--------------------------------------------------------------------------
*/
Route::get('/', [LandingController::class, 'index'])->name('home');
Route::get('/about', [LandingController::class, 'about'])->name('about');
Route::get('/packages', [LandingController::class, 'packages'])->name('packages');
Route::get('/contact', [LandingController::class, 'contact'])->name('contact');
Route::get('/faq', [LandingController::class, 'faq'])->name('faq');

/*
|--------------------------------------------------------------------------
| AUTHENTICATION
|--------------------------------------------------------------------------
*/
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.post');
Route::get('/check-username/{username}', [RegisterController::class, 'checkUsername']);
Route::get('/check-referral/{code}', [RegisterController::class, 'checkReferralCode']);

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| EMAIL VERIFICATION
|--------------------------------------------------------------------------
*/
Route::get('/email/verify', [VerifyController::class, 'showVerifyForm'])->name('verification.notice');
Route::post('/email/verify', [VerifyController::class, 'verify'])->name('verification.verify');
Route::post('/email/verify/resend', [VerifyController::class, 'resend'])->name('verification.resend');

/*
|--------------------------------------------------------------------------
| PAYMENT (AFTER AUTH + VERIFICATION)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/payment', [PaymentController::class, 'showPayment'])->name('payment.show');
    Route::post('/payment/bank-transfer', [PaymentController::class, 'processBankTransfer'])->name('payment.bank-transfer');
    Route::post('/payment/activate', [PaymentController::class, 'activateUser'])->name('payment.activate');
});

/*
|--------------------------------------------------------------------------
| REDIRECT DASHBOARD
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->get('/dashboard', function () {
    return redirect()->route('member.dashboard');
});

/*
|--------------------------------------------------------------------------
| MEMBER ROUTES
|--------------------------------------------------------------------------
*/
Route::prefix('member')->name('member.')->middleware(['auth', 'verified'])->group(function () {
    // Genealogy
    Route::get('/genealogy', [GenealogyController::class, 'index'])->name('genealogy.index');
});

/*
|--------------------------------------------------------------------------
| MEMBER WALLET ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])
    ->prefix('wallet')
    ->name('member.wallet.')
    ->group(function () {

        // Dashboard
        Route::get('/', [WalletController::class, 'index'])->name('index');

        // Wallet Sections
        Route::get('commission', [WalletController::class, 'commission'])->name('commission');
        Route::get('shopping', [WalletController::class, 'shopping'])->name('shopping');
        Route::get('transactions', [WalletController::class, 'transactions'])->name('transactions');
        Route::get('funding', [WalletController::class, 'funding'])->name('funding');

        // ✅ PAYMENT INIT – still needed to redirect to gateway
        Route::post('paystack/init', [WalletController::class, 'initPaystack'])->name('paystack.init');
        Route::post('flutterwave/init', [WalletController::class, 'initFlutterwave'])->name('flutterwave.init');

        // ✅ NEW – process successful payment via AJAX (no gateway verification)
        Route::post('payment/success', [WalletController::class, 'paymentSuccess'])->name('payment.success');

        // ✅ Manual Bank Transfer
        Route::post('request', [WalletController::class, 'requestFunding'])->name('request');

        // ❌ VERIFY ROUTES REMOVED – no longer needed
        // Route::get('paystack/verify', ...) – DELETED
        // Route::get('flutterwave/verify', ...) – DELETED
    });

 Route::get('/shopping/receipt/{order}', [App\Http\Controllers\Member\ShoppingController::class, 'receipt'])
    ->name('member.shopping.receipt')
    ->middleware('auth');
/*
|--------------------------------------------------------------------------
| EXTERNAL ROUTE FILES
|--------------------------------------------------------------------------
*/
require __DIR__ . '/member.php';
require __DIR__ . '/admin.php';