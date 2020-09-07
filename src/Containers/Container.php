<?php

namespace Sinnbeck\LaravelServed\Containers;

use Sinnbeck\LaravelServed\Shell\Shell;
use Sinnbeck\LaravelServed\Traits\Storage;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

abstract class Container
{
    use Storage;

    protected $name;
    protected $config;
    /**
     * @var \Sinnbeck\LaravelServed\Shell\Shell
     */
    protected $shell;

    public function __construct($name, $config, Shell $shell)
    {
        $this->name = $name;
        $this->config = $config;
        $this->shell = $shell;

        $this->parseConfig();
    }

    public function prepare(): self
    {
        $this->remove();

        return $this;
    }

    public function run()
    {
        //
    }

    public function parseConfig()
    {
        foreach ($this->config as $key => $value) {
            if ($key == 'port') {
                $this->setPort($value);
            }

        }
    }

    public function start()
    {
        $this->shell->exec('docker start ' . $this->makeContainerName());
    }

    public function stop()
    {
        $this->shell->exec('docker stop ' . $this->makeContainerName());
    }

    protected function env()
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
        $process = Process::fromShellCommandline('docker exec -ti "${:container}" bash');
        $process->setTimeout(null);
        $process->setTty(true);

        $process->run(null, ['container' => $this->makeContainerName()]);
    }

    protected function makeContainerName()
    {
        return sprintf('served_%s_%s', $this->projectName(), $this->name());
    }

    public function setPort($port)
    {
        if ($port) {
            $this->port = $port;

        }

        return $this;
    }

    public function port()
    {
        return $this->port;
    }

    protected function projectName()
    {
        return config('served.name');
    }

    public function name()
    {
        return $this->name;
    }

    protected function findDockerFile()
    {
        return $this->storageDirectory() . 'Dockerfile';
    }

    //Duplicate code!
    protected function makeImageName()
    {
        return sprintf('served/%s_%s', $this->projectName(), $this->name());
    }

}
