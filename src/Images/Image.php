<?php

namespace Sinnbeck\LaravelServed\Images;

use Sinnbeck\LaravelServed\Shell\Shell;
use Sinnbeck\LaravelServed\Traits\Storage;
use Sinnbeck\LaravelServed\Docker\DockerFileBuilder;
use Symfony\Component\Process\Exception\ProcessFailedException;

abstract class Image implements ImageInterface
{
    use Storage;

    protected $name;
    protected $config;

    /**
     * @var \Sinnbeck\LaravelServed\Shell\Shell
     */
    protected $shell;

    /**
     * @var \Sinnbeck\LaravelServed\Docker\DockerFileBuilder
     */
    protected $dockerFileBuilder;

    protected $library = 'library';
    protected $tag = 'latest';
    protected $tagAddition = '';
    protected $buildCommand = '';
    protected $buildFlags = ' --no-cache';

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

    public function parseConfig()
    {
        foreach ($this->config as $key => $value) {
            if ($key == 'version') {
                $this->setImageTag($value);
            }

            if ($key == 'alias') {
                $this->setAlias($value);
            }

        }
    }

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

    public function imageExists()
    {
        try {
            $this->shell->exec(sprintf('docker inspect image %s', $this->makeImageName()));
            return true;
        } catch (ProcessFailedException $e) {
            return false;
        }

    }

    public function remove()
    {
        $this->shell->exec(sprintf('docker rmi %s -f', $this->makeImageName()));
    }

    protected function makeImageName()
    {
        return sprintf('served/%s_%s', $this->projectName(), $this->name());
    }

    public function imageName()
    {
        return sprintf('%s/%s', $this->library, $this->image);
    }

    public function imageTag(): string
    {
        return sprintf('%s%s', $this->tag, $this->tagAddition);
    }

    public function setImageTag($tag): self
    {
        if ($tag) {
            $this->tag = $tag;

        }

        return $this;
    }

    public function setAlias($alias): self
    {
        if ($alias) {
            $this->alias = $alias;
        }

        return $this;
    }

    protected function projectName(): string
    {
        return config('served.name');
    }

    public function simpleName()
    {
        return strtolower(class_basename($this));
    }

    public function name()
    {
        return $this->name;
    }

    protected function storeDockerfile(string $content)
    {
        $storagePath = $this->storageDirectory();
        file_put_contents($storagePath .'Dockerfile', $content);
    }

    protected function copyDockerFile($filePath, $targetName)
    {
        $storagePath = $this->storageDirectory();
        copy($filePath, $storagePath . $targetName);
    }

    protected function findDockerFile()
    {
        return $this->storageDirectory() . 'Dockerfile';
    }

}
