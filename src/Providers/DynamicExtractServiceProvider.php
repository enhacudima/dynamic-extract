<?php
namespace Enhacudima\DynamicExtract\Providers;

use Illuminate\Support\ServiceProvider;
use Enhacudima\DynamicExtract\Console\Commands\InstallCommand;
use Enhacudima\DynamicExtract\Console\Commands\InstallTables;
use Enhacudima\DynamicExtract\Console\Commands\InstallTablesList;
use Enhacudima\DynamicExtract\Console\Commands\DeleteExportedFiles;
use Enhacudima\DynamicExtract\Console\Commands\AccessCommand;
use Enhacudima\DynamicExtract\Console\Commands\AccessRevokeCommand;
use Enhacudima\DynamicExtract\Console\Commands\AccessListCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Pagination\Paginator;
class DynamicExtractServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'extract-view');
        $this->publishes([
            __DIR__.'/../../resources/assets' => public_path('enhacudima/dynamic-extract'),
        ], 'public');

        $this->publishes([
            __DIR__.'/../../src/DataBase/Migration' => database_path('migrations')
        ], 'dynamic-extract-migrations');

        if ($this->app->runningInConsole()) {

            $this->publishes([
            __DIR__.'/../../config/config.php' => config_path('dynamic-extract.php'),
            ], 'config');

        }
        // Schedule the command if we are using the application via the CLI
        if ($this->app->runningInConsole()) {
            $this->app->booted(function () {
                $schedule = $this->app->make(Schedule::class);
                $schedule->command('dynamic-extract:delete-exported')->weekly();
            });
        }

        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCommand::class,
                InstallTables::class,
                InstallTablesList::class,
                DeleteExportedFiles::class,
                AccessCommand::class,
                AccessRevokeCommand::class,
                AccessListCommand::class,
            ]);
        }
        Paginator::useBootstrap();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        include __DIR__.'/../../routes/web.php';
        $this->mergeConfigFrom(__DIR__.'/../../config/config.php', 'dynamic-extract');
    }
}
