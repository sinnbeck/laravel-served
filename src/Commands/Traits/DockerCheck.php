<?php

namespace Sinnbeck\LaravelServed\Commands\Traits;

use Sinnbeck\LaravelServed\Docker\Docker;
use Sinnbeck\LaravelServed\Exceptions\DockerNotRunningException;
use Sinnbeck\LaravelServed\Exceptions\DockerNotInstalledException;

trait DockerCheck
{
    /**
     * @param Docker $docker
     */
    protected function checkPrerequisites(Docker $docker): void
    {
        try {
            $docker->verifyDockerIsInstalled();

        } catch (DockerNotInstalledException $e) {
            $this->error('Docker is not installed!');
        }

        try {
            $docker->verifyDockerDemonIsRunning();

        } catch (DockerNotRunningException $e) {
            $this->error('Docker daemon isn\'t running!');
        }
    }
}
