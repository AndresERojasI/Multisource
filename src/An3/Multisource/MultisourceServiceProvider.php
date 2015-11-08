<?php

namespace An3\Multisource;

use Illuminate\Support\ServiceProvider;

class MultisourceServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     */
    public function boot()
    {
        Auth::extend('multilogin', function ($app) {
            return new AuthProvider($app['hash'], $app['config']->get('auth.model'));
        });
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
    }
}
