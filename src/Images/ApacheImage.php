<?php

namespace Sinnbeck\LaravelServed\Images;

class ApacheImage extends Image
{
    protected $library = 'webdevops';
    protected $image = 'apache';
    protected $tag = 'latest';

    protected $buildCommand = 'docker build -t  "${:imagename}" . -f "${:dockerfile}"';

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
            ->env('WEB_PHP_SOCKET', 'served_php:9000')
            ->env('WEB_DOCUMENT_ROOT', '/app/public')
            ->env('WEB_PHP_TIMEOUT', '60');

        return (string) $command;
    }


}
