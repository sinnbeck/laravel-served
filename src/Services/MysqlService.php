<?php


namespace Sinnbeck\LaravelServed\Services;

use Sinnbeck\LaravelServed\Images\Image;
use Sinnbeck\LaravelServed\Images\MysqlImage;
use Sinnbeck\LaravelServed\Containers\Container;
use Sinnbeck\LaravelServed\Containers\MysqlContainer;

class MysqlService extends Service
{
    protected $serviceName = 'Mysql';

    public function container(): Container
    {
        return new MysqlContainer($this->name, $this->config, $this->shell);
    }

    public function image(): Image
    {
        return new MysqlImage($this->name, $this->config, $this->shell);
    }
}
