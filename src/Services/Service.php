<?php

namespace Sinnbeck\LaravelServed\Services;

use Exception;
use Illuminate\Support\Str;

abstract class Service
{
    protected $image = null;

    protected $container = null;

    public function build()
    {
        return $this->image()->build();
    }

    public function run()
    {
        return $this->container()->run();
    }

    public function setImageFromName($name)
    {
        $image = config('served.images.' . $name);
        if (!$image) {
            throw new Exception('Image not found!');
        }
        $this->image = app($image);
        return $this;
    }

    public function image()
    {
        if (isset($this->image)) {
            return $this->image;
        }

        $namespace = 'Sinnbeck\\LaravelServed\\Images\\';
        return $this->image = app($namespace . class_basename($this));

        $image = config('served.images.' . $this->simpleName());
        // dd(strtolower($this->name()), config('served.images'), $image);

        if (!$image) {
            throw new Exception('Image not found!');
        }

        return app($image);
        // $namespace = 'Sinnbeck\\LaravelServed\\Images\\';
        // return $this->image = app($namespace . class_basename($this));
    }

    public function setContainerFromName($name)
    {
        $namespace = 'Sinnbeck\\LaravelServed\\Containers\\';
        try {
            $container = app($namespace . Str::studly($name));

        } 
        catch (\Illuminate\Contracts\Container\BindingResolutionException $e) {
            throw new Exception('Container not found!');
        }

        $this->container = $container;
        return $this;
    }

    public function container()
    {
        if (isset($this->container)) {
            return $this->container;
        }

        
        $namespace = 'Sinnbeck\\LaravelServed\\Containers\\';
        return $this->container = app($namespace . class_basename($this));
    }

    public function name()
    {
        return class_basename($this);
    }

    public function simpleName()
    {
        return strtolower($this->name());
    }

}
