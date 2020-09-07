<?php

namespace Sinnbeck\LaravelServed\Images;

class NginxImage extends Image
{
    protected $image = 'nginx';
    protected $tag = '1.19';
    protected $buildCommand = 'docker build -t "${:imagename}" . -f "${:dockerfile}"';

    protected function prepareConfFiles()
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

    public function writeDockerFile(): string
    {
        $command = $this->dockerFileBuilder
            ->from($this->imageName(), $this->imageTag())
            ->newLine()
            ->comment('Copy in new nginx config')
//            ->copy('storage/served/nginx/default.conf', '/etc/nginx/conf.d/default.conf');
            ->copy($this->storageDirectory(true) . 'default.conf', '/etc/nginx/conf.d/default.conf');

        return (string) $command;
    }


}
