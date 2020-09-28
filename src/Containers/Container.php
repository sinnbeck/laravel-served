<?php

namespace Sinnbeck\LaravelServed\Containers;

use Sinnbeck\LaravelServed\Exceptions\TtyNotSupportedException;
use Sinnbeck\LaravelServed\Shell\Shell;
use Sinnbeck\LaravelServed\Traits\Storage;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

abstract class Container
{
    use Storage;

    /**
     * @var string
     */
    protected $name;
    /**
     * @var
     */
    protected $config;
    /**
     * @var Shell
     */
    protected $shell;

    /**
     * Container constructor.
     * @param string $name
     * @param $config
     * @param Shell $shell
     */
    public function __construct(string $name, $config, Shell $shell)
    {
        $this->name = $name;
        $this->config = $config;
        $this->shell = $shell;

        $this->parseConfig();
    }

    /**
     * @return $this
     */
    public function prepare(): self
    {
        $this->remove();

        return $this;
    }

    public function run()
    {
        //
    }

    /**
     * @return void
     */
    public function parseConfig(): void
    {
        foreach ($this->config as $key => $value) {
            if ($key === 'port') {
                $this->setPort($value);
            }
        }
    }

    /**
     * @return void
     */
    public function start(): void
    {
        $this->shell->exec('docker start ' . $this->makeContainerName());
    }

    /**
     * @return void
     */
    public function stop(): void
    {
        $this->shell->exec('docker stop ' . $this->makeContainerName());
    }

    /**
     * @return array
     */
    protected function env()
    {
        $baseEnv = [
            'network' => $this->projectName(),
            'container_name' => $this->makeContainerName(),
            'image_name' => $this->makeImageName(),
        ];

        return array_merge($baseEnv, $this->getAdditionalEnv());
    }

    /**
     * @return array
     */
    protected function getAdditionalEnv(): array
    {
        return [];
    }

    /**
     * @return void
     */
    public function remove(): void
    {
        try {
            $this->shell->exec('docker rm ' . $this->makeContainerName() . ' -f');

        } catch (ProcessFailedException $e) {
            //Do nothing
        }
    }

    /**
     * @throws TtyNotSupportedException
     */
    public function ssh(): void
    {
        if (!Process::isTtySupported()) {
            throw new TtyNotSupportedException('TTY mode is not supported');
        }

        $process = Process::fromShellCommandline('docker exec -ti "${:container}" bash');

        $process->setTimeout(null);
        $process->setTty(true);

        $process->run(null, ['container' => $this->makeContainerName()]);
    }

    /**
     * @return string
     */
    public function fallbackSsh(): string
    {
        return sprintf('docker exec -ti %s bash', $this->makeContainerName());
    }

    /**
     * @return string
     */
    protected function makeContainerName(): string
    {
        return sprintf('served_%s_%s', $this->projectName(), $this->name());
    }

    /**
     * @param string $port
     * @return $this
     */
    public function setPort(string $port): self
    {
        if ($port) {
            $this->port = $port;
        }

        return $this;
    }

    /**
     * @return string
     */
    public function port(): string
    {
        return $this->port;
    }

    /**
     * @return string
     */
    public function projectName(): string
    {
        return app('served.name');
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    protected function findDockerFile(): string
    {
        return $this->storageDirectory() . 'Dockerfile';
    }

    //Duplicate code!

    /**
     * @return string
     */
    protected function makeImageName(): string
    {
        return sprintf('served/%s_%s', $this->projectName(), $this->name());
    }

}
