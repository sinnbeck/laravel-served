<?php

namespace Sinnbeck\LaravelServed\Services;

use Sinnbeck\LaravelServed\Containers\Container;
use Sinnbeck\LaravelServed\Containers\PhpContainer;
use Sinnbeck\LaravelServed\Images\Image;
use Sinnbeck\LaravelServed\Images\PhpImage;

class PhpService extends Service
{
    /**
     * @var string
     */
    protected $serviceName = 'Php';

    /**
     * @inheritDoc
     */
    public function container(): Container
    {
        return new PhpContainer($this->name, $this->config, $this->shell);
    }

    /**
     * @inheritDoc
     */
    public function image(): Image
    {
        return new PhpImage($this->name, $this->config, $this->shell);
    }
}
