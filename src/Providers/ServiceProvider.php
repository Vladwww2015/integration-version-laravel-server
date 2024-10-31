<?php

namespace IntegrationHelper\IntegrationVersionLaravelServer\Providers;


use IntegrationHelper\IntegrationVersionLaravelServer\Console\Commands\ResetIndex;
use IntegrationHelper\IntegrationVersionLaravelServer\Console\Commands\ResetIndexAll;
use IntegrationHelper\IntegrationVersionLaravelServer\Console\Commands\RunReindex;
use IntegrationHelper\IntegrationVersionLaravelServer\Console\Commands\RunReindexAll;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../Routes/admin-routes.php');
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerCommands();
        $this->registerConfig();
    }

    protected function registerCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                ResetIndex::class,
                ResetIndexAll::class,
                RunReindex::class,
                RunReindexAll::class
            ]);
        }
    }

    /**
     * Register package config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/acl.php', 'acl'
        );
    }
}
