<?php

namespace Sinnbeck\LaravelServed\Images;

use Sinnbeck\LaravelServed\Shell\Shell;
use Sinnbeck\LaravelServed\Traits\Storage;
use Sinnbeck\LaravelServed\Docker\DockerFileBuilder;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

abstract class Image
{
    use Storage;
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

    public function __construct(Shell $shell, DockerFileBuilder $dockerFileBuilder)
    {
        $this->shell = $shell;
        $this->dockerFileBuilder = $dockerFileBuilder;
    }

    public function build($noCache = false)
    {
        $this->prepareConfigFiles();
        $this->generateDockerFile();
        $this->shell->run($this->buildCommand . ($noCache ? $this->buildFlags : ''), $this->prepareEnv());
    }

    protected function prepareEnv()
    {
        return [];
    }

    protected function prepareConfigFiles()
    {
        //
    }

    protected function generateDockerFile()
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
//        return !!$this->shell->exec(sprintf('docker images %s -q', $this->makeImageName()));
    }

    public function remove()
    {
        $this->shell->exec(sprintf('docker rmi %s -f', $this->makeImageName()));
    }

    protected function makeImageName()
    {
        return sprintf('served/%s_%s', $this->projectName(), $this->serviceName);
    }

    public function imageName()
    {
        return sprintf('%s/%s', $this->library, $this->image);
    }

    public function imageTag()
    {
        return sprintf('%s%s', $this->tag, $this->tagAddition);
    }

    public function setImageTag($tag)
    {
        if ($tag) {
            $this->tag = $tag;

        }

        return $this;
    }

    protected function projectName()
    {
        return config('served.name');
    }

    public function simpleName()
    {
        return strtolower(class_basename($this));
    }

    public function name()
    {
        return class_basename($this);
    }

    protected function storeDockerfile(string $content)
    {
        $storagePath = $this->storageDirectory();
        file_put_contents($storagePath .'/Dockerfile', $content);
    }

    protected function copyDockerFile($filePath, $targetName)
    {
        $storagePath = $this->storageDirectory();
        copy($filePath, $storagePath . '/' . $targetName);
    }

    protected function findDockerFile()
    {
        return $this->storageDirectory() . '/Dockerfile';
    }

}
