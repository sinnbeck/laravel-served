<?php

namespace Sinnbeck\LaravelServed;

use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Sinnbeck\LaravelServed\Services\RedisService;
use Sinnbeck\LaravelServed\Services\ApacheService;
use Sinnbeck\LaravelServed\Services\MysqlService;
use Sinnbeck\LaravelServed\Services\NginxService;
use Sinnbeck\LaravelServed\Services\PhpService;
use Sinnbeck\LaravelServed\Shell\Shell;
use Sinnbeck\LaravelServed\Services\MailhogService;
use Sinnbeck\LaravelServed\Services\PostgresService;

class ServiceManager
{
    /**
     * @var array
     */
    protected $extendables = [];

    /**
     * @var Shell
     */
    private $shell;

    /**
     * @return Collection
     * @throws Exception
     */
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

    /**
     * ServiceManager constructor.
     * @param Shell $shell
     */
    public function __construct(Shell $shell)
    {
        $this->shell = $shell;
    }

    /**
     * @param string $service
     * @param $callback
     */
    public function extend(string $service, $callback)
    {
        $this->extendables[$service] = $callback;
    }

    /**
     * @return mixed|ApacheService|MysqlService|NginxService|PhpService
     * @throws Exception
     */
    public function php()
    {
        $config = config('served.php');
        return $this->resolve('php', Arr::get($config, 'service', 'php'), $config);
    }

    /**
     * @return mixed|ApacheService|MysqlService|NginxService|PhpService
     * @throws Exception
     */
    public function web()
    {
        $config = config('served.web');
        return $this->resolve('web', Arr::get($config, 'service', 'nginx'), $config);
    }

    /**
     * @param string $name
     * @param string $service
     * @param array $config
     * @return mixed|ApacheService|MysqlService|NginxService|PhpService
     * @throws Exception
     */
    public function resolve(string $name, string $service, array $config)
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

            case 'postgres':
            case 'pgsql':
                return new  PostgresService($name, $config, $this->shell);

            case 'redis':
                return new  RedisService($name, $config, $this->shell);

            case 'mailhog':
                return new  MailhogService($name, $config, $this->shell);

            case null:
                throw new Exception('No service specified for ' . $name);

            default:
                throw new Exception('Service ', $service . ' wasn\'t found for ' . $name);

        }
    }

    /**
     * @param string $name
     * @return mixed|ApacheService|MysqlService|NginxService|PhpService
     * @throws Exception
     */
    public function resolveByName(string $name)
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

        throw new Exception('Could not resolve ' . $name);
    }
}
