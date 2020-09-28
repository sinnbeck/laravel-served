<?php

namespace Sinnbeck\LaravelServed\Services;

use Sinnbeck\LaravelServed\Images\MailhogImage;
use Sinnbeck\LaravelServed\Containers\Container;
use Sinnbeck\LaravelServed\Images\Image;
use Sinnbeck\LaravelServed\Containers\MailhogContainer;

class MailhogService extends Service
{
    /**
     * @var string
     */
    protected $serviceName = 'Mailhog';

    /**
     * @inheritDoc
     */
    public function container(): Container
    {
        return new MailhogContainer($this->name, $this->config, $this->shell);
    }

    /**
     * @inheritDoc
     */
    public function image(): Image
    {
        return new MailhogImage($this->name, $this->config, $this->shell);
    }
}
