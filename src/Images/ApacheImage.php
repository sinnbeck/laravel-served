<?php

namespace Sinnbeck\LaravelServed\Images;

class ApacheImage extends Image
{
    /**
     * @var string
     */
    protected $library = 'webdevops';

    /**
     * @var string
     */
    protected $image = 'apache';

    /**
     * @var string
     */
    protected $tag = 'latest';

    /**
     * @var string
     */
    protected $buildCommand = 'docker build -t  "${:imagename}" . -f "${:dockerfile}"';

    /**
     * @return void
     */
    protected function prepareConfFiles(): void
    {
        $this->copyDockerFile(__DIR__ . '/stubs/nginx.conf', 'default.conf');
    }

    /**
     * @return array
     */
    protected function prepareEnv(): array
    {
        return [
            'imagename' => $this->makeImageName(),
            'uid' => getmyuid(),
            'dockerfile' => $this->findDockerFile(),
        ];
    }

    /**
     * @return string
     */
    public function writeDockerFile(): string
    {
        $command = $this->dockerFileBuilder
            ->from($this->imageName(), $this->imageTag())
            ->env('WEB_PHP_SOCKET', 'served_php:9000')
            ->env('WEB_DOCUMENT_ROOT', '/app/public')
            ->env('WEB_PHP_TIMEOUT', '60');

        return (string)$command;
    }
}
