<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\VerifyController;
use App\Http\Controllers\Auth\PaymentController;

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
| PAYMENT AFTER VERIFICATION
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/payment', [PaymentController::class, 'showPayment'])->name('payment.show');
    Route::post('/payment/bank-transfer', [PaymentController::class, 'processBankTransfer'])->name('payment.bank-transfer');
    Route::post('/payment/activate', [PaymentController::class, 'activateUser'])->name('payment.activate');
});

/*
|--------------------------------------------------------------------------
| MEMBER & ADMIN ROUTES – LOAD EXTERNAL FILES
|--------------------------------------------------------------------------
*/
require __DIR__.'/member.php';
require __DIR__.'/admin.php';

/*
|--------------------------------------------------------------------------
| REDIRECT /dashboard TO MEMBER DASHBOARD
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->get('/dashboard', function () {
    return redirect()->route('member.dashboard');
});