<?php

namespace Sinnbeck\LaravelServed\Services;

use Sinnbeck\LaravelServed\Images\Image;
use Sinnbeck\LaravelServed\Images\PhpImage;
use Sinnbeck\LaravelServed\Containers\Container;
use Sinnbeck\LaravelServed\Containers\PhpContainer;

class PhpService extends Service
{
    protected $serviceName = 'Php';

    public function container(): Container
    {
        return new PhpContainer($this->name, $this->config, $this->shell);
    }

    public function image(): Image
    {
        return new PhpImage($this->name, $this->config, $this->shell);
    }
}
