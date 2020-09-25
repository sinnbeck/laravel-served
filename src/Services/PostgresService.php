<?php

namespace Sinnbeck\LaravelServed\Services;

use Sinnbeck\LaravelServed\Containers\Container;
use Sinnbeck\LaravelServed\Images\Image;
use Sinnbeck\LaravelServed\Images\PostgresImage;
use Sinnbeck\LaravelServed\Containers\PostgresContainer;

class PostgresService extends Service
{
    /**
     * @var string
     */
    protected $serviceName = 'Postgres';

    /**
     * @inheritDoc
     */
    public function container(): Container
    {
        return new PostgresContainer($this->name, $this->config, $this->shell);
    }

    /**
     * @inheritDoc
     */
    public function image(): Image
    {
        return new PostgresImage($this->name, $this->config, $this->shell);
    }
}
