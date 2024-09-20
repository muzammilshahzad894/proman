<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
         Schema::defaultStringLength(191);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        
            $loader = \Illuminate\Foundation\AliasLoader::getInstance();

            //$this->app->register('Barryvdh\DomPDF\ServiceProvider::class');
            //$loader->alias('PDF', 'Barryvdh\DomPDF\Facade::class');

            
            $this->app->register('App\Providers\AuthServiceProvider');
            

        

        
    }
}
