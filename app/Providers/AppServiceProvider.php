<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('help', function(){
            return new \App\Library\Help;
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // $this->configCache();
        // $this->routesCache();
        Schema::defaultStringLength(191);
    }

    private function configCache()
    {
        // load_config_into_cache();
    }

    private function routesCache()
    {
        // load_routes_into_cache();
    }
}
