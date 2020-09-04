<?php

namespace Sinnbeck\LaravelServed\Images;

class Nginx extends Image
{
    protected $image = 'nginx';
    protected $tag = '1.19';
    protected $buildCommand = 'docker build -t "$imagename" --build-arg uid="$uid" . -f "$dockerfile"';
    protected $serviceName = 'web';

    protected function prepareConfigFiles()
    {
        $this->copyDockerFile(__DIR__.'/stubs/nginx.conf', 'default.conf');
    }

    protected function prepareEnv()
    {
        return [
            'imagename' => $this->makeImageName(),
            'uid' => getmyuid(),
            'dockerfile' => $this->findDockerFile(),
        ];
    }

    public function generateDockerFile(): string
    {
        $command = $this->dockerFileBuilder
            ->from($this->imageName(), $this->imageTag())
            ->newLine()
            ->comment('Copy in new nginx config')
//            ->copy('storage/served/nginx/default.conf', '/etc/nginx/conf.d/default.conf');
            ->copy($this->storageDirectory(true) . 'default.conf', '/etc/nginx/conf.d/default.conf');

        $this->storeDockerfile($command);
        return (string) $command;
    }


}
