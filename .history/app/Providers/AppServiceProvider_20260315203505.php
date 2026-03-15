<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        //
    }



public function boot()
{
    Validator::extend('current_password', function ($attribute, $value, $parameters, $validator) {
        $guard = $parameters[0] ?? 'web';
        $user = auth()->guard($guard)->user();
        return $user && Hash::check($value, $user->password);
    }, 'The current password is incorrect.');
}
}
