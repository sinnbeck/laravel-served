<?php

namespace Sinnbeck\LaravelServed;

use Illuminate\Support\ServiceProvider;
use Sinnbeck\LaravelServed\Commands\ServedListCommand;
use Sinnbeck\LaravelServed\Commands\ServedSshCommand;
use Sinnbeck\LaravelServed\Commands\ServedStartCommand;
use Sinnbeck\LaravelServed\Commands\ServedStopCommand;
use Sinnbeck\LaravelServed\Commands\ServedTearDownCommand;
use Sinnbeck\LaravelServed\Commands\ServedUpCommand;

class ServedServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/config/served.php', 'served'
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/served.php' => config_path('served.php')
        ], 'served-config');

        if ($this->app->runningInConsole()) {
            $this->commands([
                ServedUpCommand::class,
                ServedStartCommand::class,
                ServedStopCommand::class,
                ServedTearDownCommand::class,
                ServedListCommand::class,
                ServedSshCommand::class,
            ]);
        }

        $this->app->singleton('served.name', function () {
            return (new ServedName())->projectName();
        });
    }
}
