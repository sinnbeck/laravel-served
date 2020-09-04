<?php

namespace Sinnbeck\LaravelServed\Commands\Traits;

trait DockerCheck
{
    protected function checkPrequisites($docker)
    {
        $docker->verifyDockerIsInstalled();

        $docker->verifyDockerDemonIsRunning();
    }
}
