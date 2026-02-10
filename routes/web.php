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
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
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

// AJAX endpoints
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

// ================= PAYMENT ROUTES =================
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/payment', [PaymentController::class, 'showPayment'])->name('payment.show');
    Route::post('/payment/bank-transfer', [PaymentController::class, 'processBankTransfer'])->name('payment.bank-transfer');
    Route::post('/payment/activate', [PaymentController::class, 'activateUser'])->name('payment.activate');
});

// ================= MEMBER AREA ROUTES =================
Route::prefix('member')->name('member.')->middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [\App\Http\Controllers\Member\DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [\App\Http\Controllers\Member\ProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [\App\Http\Controllers\Member\ProfileController::class, 'update'])->name('profile.update');

    // Genealogy
    Route::get('/genealogy', [\App\Http\Controllers\Member\GenealogyController::class, 'index'])->name('genealogy');

    // Wallet
    Route::get('/wallet', [\App\Http\Controllers\Member\WalletController::class, 'index'])->name('wallet');

    // Shopping
    Route::get('/shopping', [\App\Http\Controllers\Member\ShoppingController::class, 'index'])->name('shopping');

    // VTU Services
    Route::get('/vtu', [\App\Http\Controllers\Member\VTUController::class, 'index'])->name('vtu');

    // Referrals
    Route::get('/referrals', [\App\Http\Controllers\Member\ReferralController::class, 'index'])->name('referrals');

    // Commissions
    Route::get('/commissions', [\App\Http\Controllers\Member\CommissionController::class, 'index'])->name('commissions');

    // Ranks
    Route::get('/ranks', [\App\Http\Controllers\Member\RankController::class, 'index'])->name('ranks');

    // Withdraw
    Route::get('/withdraw', [\App\Http\Controllers\Member\WithdrawController::class, 'index'])->name('withdraw');
    Route::post('/withdraw', [\App\Http\Controllers\Member\WithdrawController::class, 'store'])->name('withdraw.store');

    // Settings
    Route::get('/settings', [\App\Http\Controllers\Member\SettingController::class, 'index'])->name('settings');
    Route::put('/settings', [\App\Http\Controllers\Member\SettingController::class, 'update'])->name('settings.update');

    // Orders
    Route::get('/orders', [\App\Http\Controllers\Member\OrderController::class, 'index'])->name('orders');

    // Claim Product
    Route::get('/claim-product', [\App\Http\Controllers\Member\ClaimProductController::class, 'index'])->name('claim-product');
    Route::post('/claim-product', [\App\Http\Controllers\Member\ClaimProductController::class, 'store'])->name('claim-product.store');
});

// Redirect `/dashboard` to `member.dashboard`
Route::middleware(['auth', 'verified'])->get('/dashboard', function () {
    return redirect()->route('member.dashboard');
});
