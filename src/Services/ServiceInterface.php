<?php

namespace Sinnbeck\LaravelServed\Services;

use Sinnbeck\LaravelServed\Images\Image;
use Sinnbeck\LaravelServed\Containers\Container;

interface ServiceInterface
{
    /**
     * @return Container
     */
    public function container(): Container;

    /**
     * @return Image
     */
    public function image(): Image;
}
