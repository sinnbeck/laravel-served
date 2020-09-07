<?php

namespace Sinnbeck\LaravelServed\Commands\Traits;

use Sinnbeck\LaravelServed\Docker\Docker;
use Sinnbeck\LaravelServed\Exceptions\DockerNotInstalledException;

trait DockerCheck
{
    protected function checkPrerequisites(Docker $docker)
    {
        try {
            $docker->verifyDockerIsInstalled();

        } catch (DockerNotInstalledException $e) {
            $this->error('Docker is not installed!');
        }

        $docker->verifyDockerDemonIsRunning();
    }
}
