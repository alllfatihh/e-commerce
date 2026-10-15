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
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('site_settings')) {
                \Illuminate\Support\Facades\View::share('settings', \App\Models\SiteSetting::pluck('value', 'key')->toArray());
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\View::share('settings', []);
        }
    }
}
