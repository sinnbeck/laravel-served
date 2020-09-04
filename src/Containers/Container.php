<?php

namespace Sinnbeck\LaravelServed\Containers;

use Sinnbeck\LaravelServed\Shell\Shell;
use Sinnbeck\LaravelServed\Traits\Storage;
use Sinnbeck\LaravelServed\Docker\DockerFileBuilder;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

abstract class Container
{
    use Storage;

    /**
     * @var \Sinnbeck\LaravelServed\Shell\Shell
     */
    protected $shell;

    public function __construct(Shell $shell, DockerFileBuilder $dockerFileBuilder)
    {
        $this->shell = $shell;
        $this->dockerFileBuilder = $dockerFileBuilder;
    }

    public function run()
    {
        $this->remove();
    }

    public function createBuildString()
    {
        $base = 'docker run -d --restart always --network="$network" --name "$container_name"';

        if ($this->port) {
            $appends[] = '-p "$port":3306';
        }

        if ($this->alias) {
            $appends[] = '--network-alias "$alias"';
        }

        if ($this->volumes) {
            // foreach ($volues as $)
            $appends[] = '-v ' . implode(' ', $this->volumes);
        }

        $final = $base . implode(' ', $appends) .  ' "$image_name"';
    }

    protected function makeEnv()
    {
        $baseEnv = [
            'network' => $this->projectName(),
            'container_name' => $this->makeContainerName(),
            'image_name' => $this->makeImageName(),
        ];

        return array_merge($baseEnv, $this->getAdditionalEnv());
    }

    protected function getAdditionalEnv()
    {
        return [];
    }

    public function remove()
    {
        try {
            $this->shell->exec('docker rm ' . $this->makeContainerName() . ' -f');

        } catch (ProcessFailedException $e) {
            //Do nothing
        }
    }

    public function ssh()
    {
        $process = Process::fromShellCommandline('docker exec -ti "$container" bash');
        $process->setTimeout(null);
        $process->setTty(true);

        $process->run(null, ['container' => $this->makeContainerName()]);
    }

    protected function makeContainerName()
    {
        return sprintf('served_%s_%s', $this->projectName(), $this->serviceName());
    }

    public function setPort($port)
    {
        if ($port) {
            $this->port = $port;

        }

        return $port;
    }

    public function port()
    {
        return $this->port;
    }

    protected function projectName()
    {
        return config('served.name');
    }

    public function serviceName()
    {
        return strtolower(class_basename($this));
    }

    protected function findDockerFile()
    {
        return $this->storageDirectory() . '/Dockerfile';
    }

    //Duplicate code!
    protected function makeImageName()
    {
        return sprintf('served/%s_%s', $this->projectName(), $this->serviceName());
    }

}
