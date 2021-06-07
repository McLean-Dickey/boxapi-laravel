<?php

namespace Kaswell\BoxApi;

use Illuminate\Support\ServiceProvider;

/**
 * Class BoxApiServiceProvider
 * @package Kaswell\BoxApi
 */
class BoxApiServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/boxapi.php' => config_path('boxapi.php'),
        ], 'config');

        $this->publishes([
            __DIR__ . '/Auth/box_app_config.json' => base_path('storage/app/box_app_config.json'),
        ], 'app-config');
    }


    /**
     * Register any application services.
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/boxapi.php', 'boxapi'
        );

        $this->app->bind('BoxApi', function (){
            return new \Kaswell\BoxApi\BoxApi;
        });
    }
}