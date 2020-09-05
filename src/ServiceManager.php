<?php

namespace Sinnbeck\LaravelServed;

use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Sinnbeck\LaravelServed\Shell\Shell;
use Sinnbeck\LaravelServed\Services\PhpService;
use Sinnbeck\LaravelServed\Services\NginxService;
use Sinnbeck\LaravelServed\Services\MysqlService;
use Sinnbeck\LaravelServed\Services\ApacheService;

class ServiceManager
{
    protected $extendables = [];
    /**
     * @var \Sinnbeck\LaravelServed\Shell\Shell
     */
    private $shell;

    public function loadServices(): Collection
    {
        $php = $this->php();

        $web = $this->web();

        $baseServices = collect([$php, $web]);

        foreach (config('served.extras', []) as $name => $config) {
            $extras[] = $this->resolve($name, Arr::get($config, 'service'), $config);
        }

        return $baseServices->merge($extras);
    }

    public function __construct(Shell $shell)
    {
        $this->shell = $shell;
    }

    public function extend($service, $callback)
    {
        $this->extendables[$service] = $callback;
    }

    public function php()
    {
        $config = config('served.php');
        return $this->resolve('php', Arr::get($config, 'service', 'php'), $config);
    }

    public function web()
    {
        $config = config('served.web');
        return $this->resolve('web', Arr::get($config, 'service', 'nginx'), $config);
    }

    public function resolve($name, $service, $config)
    {
        if (isset($this->extendables[$name])) {
            return call_user_func($this->extendables, $name, $config, $this->shell);
        }

        switch ($service) {
            case 'php':
                return new  PhpService($name, $config, $this->shell);

            case 'apache':
            case 'apache2':
                return new  ApacheService($name, $config, $this->shell);

            case 'nginx':
                return new  NginxService($name, $config, $this->shell);

            case 'mysql':
                return new  MysqlService($name, $config, $this->shell);

            case null:
                throw new Exception('No service specified for ' . $name);
            default:
                throw new Exception('Service ' , $service . ' wasn\'t found for ' . $name);

        }

    }

    public function resolveByName($name)
    {
        if ($name === 'php') {
            return $this->php();
        }

        if ($name === 'web') {
            return $this->web();
        }
        if ($config = config('served.extras.' . $name)) {
            return $this->resolve($name, Arr::get($config, 'service'), $config);
        }

        throw new Exception('Could not resolve '. $name);
    }
}
