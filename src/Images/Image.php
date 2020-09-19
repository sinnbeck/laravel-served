<?php

namespace Sinnbeck\LaravelServed\Images;

use Sinnbeck\LaravelServed\Docker\DockerFileBuilder;
use Sinnbeck\LaravelServed\Shell\Shell;
use Sinnbeck\LaravelServed\Traits\Storage;
use Symfony\Component\Process\Exception\ProcessFailedException;

abstract class Image implements ImageInterface
{
    use Storage;

    protected $name;
    protected $config;

    /**
     * @var Shell
     */
    protected $shell;

    /**
     * @var DockerFileBuilder
     */
    protected $dockerFileBuilder;

    protected $library = 'library';
    protected $tag = 'latest';
    protected $tagAddition = '';
    protected $buildCommand = '';
    protected $buildFlags = ' --no-cache';

    /**
     * Image constructor.
     * @param $name
     * @param $config
     * @param Shell $shell
     */
    public function __construct($name, $config, Shell $shell)
    {
        $this->config = $config;
        $this->shell = $shell;
        $this->dockerFileBuilder = app(DockerFileBuilder::class);
        $this->name = $name;
        $this->parseConfig();
    }

    public function prepareBuild(): self
    {
        $this->prepareConfFiles();
        $dockerFile = $this->writeDockerFile();
        $this->storeDockerfile($dockerFile);

        return $this;
    }

    public function parseConfig(): void
    {
        foreach ($this->config as $key => $value) {
            if ($key === 'version') {
                $this->setImageTag($value);
            }

            if ($key === 'alias') {
                $this->setAlias($value);
            }

        }
    }

    /**
     * @param boolean $noCache
     *
     */
    public function build($noCache = false): void
    {
        $this->shell->run($this->buildCommand . ($noCache ? $this->buildFlags : ''), $this->prepareEnv());
    }

    protected function prepareEnv()
    {
        return [];
    }

    protected function prepareConfFiles()
    {
        //
    }

    /**
     * @return bool
     */
    public function imageExists(): bool
    {
        try {
            $this->shell->exec(sprintf('docker inspect image %s', $this->makeImageName()));
            return true;
        } catch (ProcessFailedException $e) {
            return false;
        }
    }

    /**
     * @return void
     */
    public function remove(): void
    {
        $this->shell->exec(sprintf('docker rmi %s -f', $this->makeImageName()));
    }

    /**
     * @return string
     */
    protected function makeImageName(): string
    {
        return sprintf('served/%s_%s', $this->projectName(), $this->name());
    }

    /**
     * @return string
     */
    public function imageName(): string
    {
        return sprintf('%s/%s', $this->library, $this->image);
    }

    /**
     * @return string
     */
    public function imageTag(): string
    {
        return sprintf('%s%s', $this->tag, $this->tagAddition);
    }

    /**
     * @param $tag
     * @return $this
     */
    public function setImageTag($tag): self
    {
        if ($tag) {
            $this->tag = $tag;
        }

        return $this;
    }

    /**
     * @param string $alias
     * @return $this
     */
    public function setAlias(string $alias): self
    {
        if ($alias) {
            $this->alias = $alias;
        }

        return $this;
    }

    /**
     * @return string
     */
    protected function projectName(): string
    {
        return config('served.name');
    }

    /**
     * @return string
     */
    public function simpleName()
    {
        return strtolower(class_basename($this));
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * @param string $content
     * @return void
     */
    protected function storeDockerfile(string $content): void
    {
        $storagePath = $this->storageDirectory();
        file_put_contents($storagePath . 'Dockerfile', $content);
    }

    /**
     * @param string $filePath
     * @param string $targetName
     * @return void
     */
    protected function copyDockerFile(string $filePath, string $targetName): void
    {
        $storagePath = $this->storageDirectory();
        copy($filePath, $storagePath . $targetName);
    }

    /**
     * @return string
     */
    protected function findDockerFile(): string
    {
        return $this->storageDirectory() . 'Dockerfile';
    }
}
