<?php

namespace Sinnbeck\LaravelServed\Services;

use Sinnbeck\LaravelServed\Images\RedisImage;
use Sinnbeck\LaravelServed\Containers\RedisContainer;
use Sinnbeck\LaravelServed\Containers\Container;
use Sinnbeck\LaravelServed\Images\Image;

class RedisService extends Service
{
    /**
     * @var string
     */
    protected $serviceName = 'Redis';

    /**
     * @inheritDoc
     */
    public function container(): Container
    {
        return new RedisContainer($this->name, $this->config, $this->shell);
    }

    /**
     * @inheritDoc
     */
    public function image(): Image
    {
        return new RedisImage($this->name, $this->config, $this->shell);
    }
}
