<?php

namespace Sinnbeck\LaravelServed\Services;

use Sinnbeck\LaravelServed\Shell\Shell;

abstract class Service implements ServiceInterface
{
    protected $image = null;

    protected $container = null;
    protected $config;
    /**
     * @var \Sinnbeck\LaravelServed\Shell\Shell
     */
    protected $shell;
    /**
     * @var string
     */
    protected $name;

    public function __construct(string $name, $config, Shell $shell)
    {
        $this->name = $name;
        $this->config = $config;
        $this->shell = $shell;
    }

    public function build()
    {
        $this->image()->prepareBuild()->build();
    }

    public function run()
    {
        $this->container()->prepare()->run();
    }

    public function name()
    {
        return $this->name;
    }

    public function serviceName()
    {
        if (isset($this->serviceName)) {
            return $this->serviceName;

        }
        return class_basename($this);
    }

    public function imageName()
    {
        return sprintf('%s %s', $this->serviceName(), $this->image()->imageTag());
    }

}
