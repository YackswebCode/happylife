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


        // Add more admin resource routes here later
    });
});