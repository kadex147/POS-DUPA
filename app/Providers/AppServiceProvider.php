<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL; // <--- BARIS YANG DITAMBAHKAN

class AppServiceProvider extends ServiceProvider
{
    // ...
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
        if ($this->app->environment('production')) {
    URL::forceScheme('https'); // Baris ini yang paling penting
        }
    }
}
