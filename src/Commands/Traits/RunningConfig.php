<?php

namespace Sinnbeck\LaravelServed\Commands\Traits;

use Sinnbeck\LaravelServed\ServiceManager;

trait RunningConfig
{
    use Logo;
    protected function servedRunning(ServiceManager $manager)
    {
        $this->line('      Laravel has been', 'fg=blue');
        $this->drawLogo();
        $url = 'http://localhost:' . $manager->web()->container()->port();
        $this->line('<fg=green>Visit the development server at:</> <fg=white><href="' . $url . '">'. $url . '</></>');
    }
}
