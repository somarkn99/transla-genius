<?php

namespace CodingPartners\AutoTranslator;

use Illuminate\Support\ServiceProvider;

/**
 * Class AutoTranslatorServiceProvider
 *
 * This service provider is responsible for bootstrapping the AutoTranslator package.
 * It handles the registration of configuration files and helper functions, and publishes
 * the package configuration file to the application's configuration directory.
 *
 * @package CodingPartners\AutoTranslator
 */
class AutoTranslatorServiceProvider extends ServiceProvider
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
        $this->mergeConfigFrom(__DIR__ . '/config/autoTranslator.php', 'autoTranslator');

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
            __DIR__ . '/config/autoTranslator.php' => config_path('autoTranslator.php'),
        ], 'config');
    }
}
