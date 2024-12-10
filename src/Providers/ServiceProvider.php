<?php

namespace IntegrationHelper\IntegrationVersionLaravelServer\Providers;

use Illuminate\Support\Facades\Route;
use IntegrationHelper\IntegrationVersionLaravelServer\Console\Commands\ClearDeletedIndexes;
use IntegrationHelper\IntegrationVersionLaravelServer\Console\Commands\ResetAndRunIndexAllQueue;
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

    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mapApiRoutes();
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
                RunReindexAll::class,
                ClearDeletedIndexes::class,
                ResetAndRunIndexAllQueue::class
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

    protected function mapApiRoutes()
    {
        Route::prefix('api')
            ->middleware('api')
            ->group(__DIR__.'/../Routes/api.php');
    }
}
