<?php

namespace Sinnbeck\LaravelServed\Services;

use Illuminate\Support\Str;

class Web extends Service
{

    public function image()
    {
        if (isset($this->image)) {
            return $this->image;
        }
        
        $namespace = 'Sinnbeck\\LaravelServed\\Images\\';
        return $this->image = app($namespace . Str::studly(config('served.web.service', 'nginx')));
    }

}
