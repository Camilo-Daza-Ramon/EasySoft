<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Dusk\DuskServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Schema::defaultStringLength(191);
        Paginator::defaultView('pagination::bootstrap-4');
        setlocale(LC_TIME, "es");
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        /*if ($this->app->environment('local', 'testing')) {
            $this->app->register(DuskServiceProvider::class);
            //URL::forceScheme('https');
        }*/

        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }        
    }
}
