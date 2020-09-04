<?php


namespace Sinnbeck\LaravelServed\Images;

class Mysql extends Image
{
    protected $image = 'mysql';
    protected $tag = '5.7';
    protected $buildCommand = 'docker build -t "$imagename" --build-arg uid="$uid" . -f "$dockerfile"';
    protected $serviceName = 'mysql';

    protected function prepareEnv()
    {
        return [
            'imagename' => $this->makeImageName(),
            'uid' => getmyuid(),
            'dockerfile' => $this->findDockerFile()
        ];
    }

    protected function generateDockerFile(): string
    {
        $command = $this->dockerFileBuilder
            ->from($this->imageName(), $this->imageTag())
            ->newLine()
            ->comment('Setting env vars for mysql init')
            ->env('MYSQL_ROOT_PASSWORD', 'password')
            ->env('MYSQL_DATABASE', 'laravel')
            ->env('MYSQL_USER', 'laravel')
            ->env('MYSQL_PASSWORD', 'password');

        $this->storeDockerfile($command);
        return (string) $command;
    }


}
