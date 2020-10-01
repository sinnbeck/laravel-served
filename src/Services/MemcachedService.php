<?php

namespace Sinnbeck\LaravelServed\Services;

use Sinnbeck\LaravelServed\Images\MemcachedImage;
use Sinnbeck\LaravelServed\Containers\Container;
use Sinnbeck\LaravelServed\Images\Image;
use Sinnbeck\LaravelServed\Containers\MemcachedContainer;

class MemcachedService extends Service
{
    /**
     * @var string
     */
    protected $serviceName = 'Memcached';

    /**
     * @inheritDoc
     */
    public function container(): Container
    {
        return new MemcachedContainer($this->name, $this->config, $this->shell);
    }

    /**
     * @inheritDoc
     */
    public function image(): Image
    {
        return new MemcachedImage($this->name, $this->config, $this->shell);
    }
}
