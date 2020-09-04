<?php

namespace Sinnbeck\LaravelServed\Services;

use Illuminate\Support\Str;

class Services
{
    public function prepareServiceList()
    {

        $php = app(Php::class);
        $php->image()->setImageTag(config('served.php.version'))
            ->setModules(config('served.php.modules', []));

        $web = app(Web::class);
        $web->image()->setImageTag(config('served.web.version'));
        $web->container()->setPort(config('served.web.port'));

        $extras = collect(config('served.extras'))->map(function($item, $key) {
            $class = app('Sinnbeck\\LaravelServed\\Services\\' . Str::studly($key));

            $class
                ->image()
                ->setImageTag($item['version'] ?? null);

            if (isset($item['port'])) {
                $class->container()->setPort($item['port']);
            };

            return $class;
        });

        $baseServices = collect([$php, $web]);

        return $baseServices->merge($extras);
    }

    protected function createClass($string)
    {
        $namespace = 'Sinnbeck\\LaravelServed\\Services\\';
        return $namespace . Str::studly($string);
    }
}
