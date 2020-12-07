<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Timesheet;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if($this->app->environment('production')) {
            dd($this->app->environment());
            \URL::forceScheme('https');
        }
    }
}
