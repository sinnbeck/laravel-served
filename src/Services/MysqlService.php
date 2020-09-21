<?php

namespace Sinnbeck\LaravelServed\Services;

use Sinnbeck\LaravelServed\Containers\Container;
use Sinnbeck\LaravelServed\Containers\MysqlContainer;
use Sinnbeck\LaravelServed\Images\Image;
use Sinnbeck\LaravelServed\Images\MysqlImage;

class MysqlService extends Service
{
    /**
     * @var string
     */
    protected $serviceName = 'Mysql';

    /**
     * @inheritDoc
     */
    public function container(): Container
    {
        return new MysqlContainer($this->name, $this->config, $this->shell);
    }

    /**
     * @inheritDoc
     */
    public function image(): Image
    {
        return new MysqlImage($this->name, $this->config, $this->shell);
    }
}
