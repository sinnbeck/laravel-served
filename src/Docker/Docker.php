<?php

namespace Sinnbeck\LaravelServed\Docker;

use Sinnbeck\LaravelServed\Exceptions\DockerNotInstalledException;
use Sinnbeck\LaravelServed\Exceptions\DockerNotRunningException;
use Sinnbeck\LaravelServed\Shell\Shell;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Process\Exception\ProcessFailedException;

class Docker
{
    /**
     * @var Shell
     */
    protected $shell;

    /**
     * Docker constructor.
     * @param Shell $shell
     * @param ConsoleOutput $consoleOutput
     */
    public function __construct(Shell $shell)
    {
        $this->shell = $shell;
    }

    /**
     * @return void
     * @throws DockerNotInstalledException
     */
    public function verifyDockerIsInstalled(): void
    {
        try {
            $this->version();

        } catch (ProcessFailedException $e) {
            throw new DockerNotInstalledException('Docker is missing!');
        }
    }

    /**
     * @return void
     * @throws DockerNotRunningException
     */
    public function verifyDockerDemonIsRunning(): void
    {
        try {
            $this->shell->exec('docker info');

        } catch (ProcessFailedException $e) {
            throw new DockerNotRunningException('Docker isn\'t running');
        }
    }

    /**
     * @return string
     */
    public function version(): string
    {
        return $this->shell->exec('docker version --format="{{json .Client.Version}}"');
    }

    /**
     * @param string $name
     * @return void
     */
    public function ensureNetworkExists(string $name): void
    {
        try {
            $this->shell->exec('docker network inspect "${:name}"', ['name' => $name]);

        } catch (ProcessFailedException $e) {
            //Make network

            $this->shell->exec('docker network create "${:name}"', ['name' => $name]);
        }
    }

    public function removeNetwork(string $name): void
    {
        $this->shell->exec('docker network rm ' . $name);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function listContainers()
    {
        $name = 'served_' . app('served.name') . '_';
        $containers = $this->shell->exec('docker ps --all --filter "name=' . $name . '" --format "{{.ID}}|{{.Names}}|{{.Image}}|{{.Status}}|{{.Ports}}"');
        $formatted = collect(explode("\n", $containers))->filter()->map(function ($row) {
            return explode('|', $row);
        })->reverse();

        return $formatted->prepend([
            'ID', 'Name', 'Image', 'Status', 'Used ports'
        ]);

    }

}
