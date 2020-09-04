<?php

namespace Sinnbeck\LaravelServed;

use Illuminate\Support\ServiceProvider;
use Sinnbeck\LaravelServed\Commands\ServedListCommand;
use Sinnbeck\LaravelServed\Commands\ServedUpCommand;
use Sinnbeck\LaravelServed\Commands\ServedSshCommand;
use Sinnbeck\LaravelServed\Commands\ServedTearDownCommand;

class ServedServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/config/served.php', 'served'
        );

        $this->app->singleton('output', Output::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/served.php' => config_path('served.php')
        ], 'config');

        if ($this->app->runningInConsole()) {
            $this->commands([
                ServedUpCommand::class,
                ServedTearDownCommand::class,
                ServedListCommand::class,
                ServedSshCommand::class,
            ]);
        }
    }
}
