<?php

namespace Sinnbeck\LaravelServed\Services;

use Sinnbeck\LaravelServed\Shell\Shell;

abstract class Service implements ServiceInterface
{
    /**
     * @var null
     */
    protected $image = null;

    /**
     * @var null
     */
    protected $container = null;

    /**
     * @var array
     */
    protected $config;

    /**
     * @var Shell
     */
    protected $shell;

    /**
     * @var string
     */
    protected $name;

    /**
     * Service constructor.
     * @param string $name
     * @param $config
     * @param Shell $shell
     */
    public function __construct(string $name, $config, Shell $shell)
    {
        $this->name = $name;
        $this->config = $config;
        $this->shell = $shell;
    }

    /**
     * @return void
     */
    public function build(): void
    {
        $this->image()->prepareBuild()->build();
    }

    /**
     * @return void
     */
    public function run(): void
    {
        $this->container()->prepare()->run();
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function serviceName(): string
    {
        if (isset($this->serviceName)) {
            return $this->serviceName;

        }
        return class_basename($this);
    }

    /**
     * @return string
     */
    public function imageName(): string
    {
        return sprintf('%s %s', $this->serviceName(), $this->image()->imageTag());
    }
}
