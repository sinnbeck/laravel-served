<?php

namespace Sinnbeck\LaravelServed\Services;

use Sinnbeck\LaravelServed\Images\Image;
use Sinnbeck\LaravelServed\Images\ApacheImage;
use Sinnbeck\LaravelServed\Containers\Container;
use Sinnbeck\LaravelServed\Containers\ApacheContainer;

class ApacheService extends Service
{
    protected $serviceName = 'Apache';

    public function container(): Container
    {
        return new ApacheContainer($this->name, $this->config, $this->shell);
    }

    public function image(): Image
    {
        return new ApacheImage($this->name, $this->config, $this->shell);
    }
}
