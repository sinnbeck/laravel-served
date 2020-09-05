<?php

namespace Sinnbeck\LaravelServed\Services;

use Sinnbeck\LaravelServed\Images\Image;
use Sinnbeck\LaravelServed\Containers\Container;

interface ServiceInterface
{
    public function container(): Container;

    public function image(): Image;
}
