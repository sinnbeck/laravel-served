<?php

namespace Sinnbeck\LaravelServed\Commands\Traits;

trait Logo
{
    /**
     * @return void
     */
    protected function drawLogo(): void
    {
        $this->line(' __   ___  __        ___  __  
/__` |__  |__) \  / |__  |  \ 
.__/ |___ |  \  \/  |___ |__/ 
                              ', 'fg=blue');
    }

    /**
     * @return void
     */
    protected function oldLogo(): void
    {
        $this->line(' _____                   _
|   __|___ ___ _ _ ___ _| |
|__   | -_|  _| | | -_| . |
|_____|___|_|  \_/|___|___|', 'fg=blue');
    }
}
