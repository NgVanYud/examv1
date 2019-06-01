<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

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
        /**
         *  Syntax error or access violation: 1071 Specified key was too long; max key length is 1000 bytes
         *  https://stackoverflow.com/a/42245921
         */
        Schema::defaultStringLength(191);
    }
}
