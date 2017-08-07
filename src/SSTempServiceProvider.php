<?php

namespace Tchoblond59\SSTemp;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class SSTempServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/assets' => public_path('/js/tchoblond59/sstemp'),
        ], 'larahome-package');
        //$this->loadRoutesFrom(__DIR__.'/routes.php');
        //$this->loadViewsFrom(__DIR__.'/views', 'sstemp');
        //$this->loadMigrationsFrom(__DIR__.'/migrations');
        Event::listen('App\Events\MSMessageEvent', 'Tchoblond59\SSTemp\EventListener\SSRelayEventListener');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
    }
}
