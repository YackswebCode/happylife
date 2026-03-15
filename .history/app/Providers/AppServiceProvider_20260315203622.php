<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Custom validation rule for current password with guard support
        Validator::extend('current_password', function ($attribute, $value, $parameters, $validator) {
            $guard = $parameters[0] ?? 'web';
            $user = auth()->guard($guard)->user();
            return $user && Hash::check($value, $user->password);
        }, 'The current password is incorrect.');
    }
}