<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function run()
    {
        Schema::defaultStringLength(191);
    }

    public function register()
    {
        //
    }
}
