<?php

namespace An3\Multisource;

use Illuminate\Support\ServiceProvider;

class MultisourceServiceProvider extends ServiceProvider
{
    /**
     * [$enabledSources description].
     *
     * @var [type]
     */
    private $enabledSources;

    /**
     * Bootstrap the application events.
     */
    public function boot()
    {
        //Registers the new Auth Provider
        \Auth::extend('multilogin', function ($app) {
            return new MultisourceDriver(SourceFactory::generateConfiguration());
        });

        //Enables the config copy from the Base Config File
        $this->publishes([
            __DIR__.DIRECTORY_SEPARATOR.'Config'.DIRECTORY_SEPARATOR.'multisource.php' => config_path('multisource.php'),
        ]);
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
    }
}
