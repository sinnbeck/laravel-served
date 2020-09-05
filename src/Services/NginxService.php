<?php

namespace Sinnbeck\LaravelServed\Services;

use Sinnbeck\LaravelServed\Images\Image;
use Sinnbeck\LaravelServed\Images\NginxImage;
use Sinnbeck\LaravelServed\Containers\Container;
use Sinnbeck\LaravelServed\Containers\NginxContainer;

class NginxService extends Service
{
    protected $serviceName = 'Nginx';

    public function container(): Container
    {
        return new NginxContainer($this->name, $this->config, $this->shell);
    }

    public function image(): Image
    {
        return new NginxImage($this->name, $this->config, $this->shell);
    }
}
