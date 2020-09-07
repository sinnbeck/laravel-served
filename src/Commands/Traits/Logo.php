<?php

namespace Sinnbeck\LaravelServed\Commands\Traits;

trait Logo
{
    protected function drawLogo()
    {
        $this->line(' __   ___  __        ___  __  
/__` |__  |__) \  / |__  |  \ 
.__/ |___ |  \  \/  |___ |__/ 
                              ', 'fg=blue');

    }

    protected function oldLogo()
    {
        $this->line(' _____                   _
|   __|___ ___ _ _ ___ _| |
|__   | -_|  _| | | -_| . |
|_____|___|_|  \_/|___|___|', 'fg=blue');
    }
}
