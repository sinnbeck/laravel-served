<?php

namespace Sinnbeck\LaravelServed\Services;

use Sinnbeck\LaravelServed\Containers\ApacheContainer;
use Sinnbeck\LaravelServed\Containers\Container;
use Sinnbeck\LaravelServed\Images\ApacheImage;
use Sinnbeck\LaravelServed\Images\Image;

class ApacheService extends Service
{
    /**
     * @var string
     */
    protected $serviceName = 'Apache';

    /**
     * @inheritDoc
     */
    public function container(): Container
    {
        return new ApacheContainer($this->name, $this->config, $this->shell);
    }

    /**
     * @inheritDoc
     */
    public function image(): Image
    {
        return new ApacheImage($this->name, $this->config, $this->shell);
    }
}
