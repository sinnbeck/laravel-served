<?php

namespace Sinnbeck\LaravelServed\Commands\Traits;

use Sinnbeck\LaravelServed\ServiceManager;

trait RunningConfig
{
    use Logo;

    /**
     * @param ServiceManager $manager
     * @throws \Exception
     */
    protected function servedRunning(ServiceManager $manager): void
    {
        $this->line('      Laravel has been', 'fg=blue');
        $this->drawLogo();
        $url = 'http://localhost:' . $manager->web()->container()->port();
        $secureUrl = 'https://localhost:' . $manager->web()->container()->sslPort();
        $this->line('<fg=green>Visit the development server at:</>');
        $this->line('<fg=white><href="' . $url . '">'. $url . '</></>');
        $this->line('<fg=white><href="' . $secureUrl . '">'. $secureUrl . '</></>');
    }
}
