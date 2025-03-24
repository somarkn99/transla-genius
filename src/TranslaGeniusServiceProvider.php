<?php

namespace CodingPartners\TranslaGenius;

use Illuminate\Support\ServiceProvider;

/**
 * Class TranslaGeniusServiceProvider
 *
 * This service provider is responsible for bootstrapping the TranslaGenius package.
 * It handles the registration of configuration files and helper functions, and publishes
 * the package configuration file to the application's configuration directory.
 *
 * @package CodingPartners\TranslaGenius
 */
class TranslaGeniusServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * This method is called when the service provider is registered. It merges the package's
     * configuration file into the application's configuration and includes any helper functions.
     *
     * @return void
     */
    public function register()
    {
        // Merge the package configuration file into the application's configuration.
        $this->mergeConfigFrom(__DIR__ . '/config/translaGenius.php', 'translaGenius');

        // Include the package's helper functions.
        require_once __DIR__ . '/helpers.php';
    }

    /**
     * Bootstrap any application services.
     *
     * This method is called when the application is booting. It publishes the package's
     * configuration file to the application's configuration directory.
     *
     * @return void
     */
    public function boot()
    {
        // Publish the configuration file to the application's configuration directory.
        $this->publishes([
            __DIR__ . '/config/translaGenius.php' => config_path('translaGenius.php'),
        ], 'config');
    }
}
