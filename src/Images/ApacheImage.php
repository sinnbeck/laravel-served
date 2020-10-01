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
    protected $buildCommand = 'docker build -t "${:imagename}" . -f "${:dockerfile}"';

    /**
     * @return void
     */
    protected function prepareConfFiles(): void
    {
        $this->copyDockerFile(__DIR__ . '/stubs/localhost.crt', 'localhost.crt');
        $this->copyDockerFile(__DIR__ . '/stubs/localhost.key', 'localhost.key');
    }

    /**
     * @return array
     */
    protected function prepareEnv(): array
    {
        return [
            'imagename'  => $this->makeImageName(),
            'dockerfile' => $this->findDockerFile(),
        ];
    }

    /**
     * @return string
     */
    public function writeDockerFile(): string
    {
        $command = $this->dockerFileBuilder
            ->from($this->imageName(), $this->tag)
            ->env('WEB_PHP_SOCKET', 'served_php:9000')
            ->env('WEB_DOCUMENT_ROOT', '/app/public')
            ->env('WEB_PHP_TIMEOUT', '60')
            ->copy($this->storageDirectory(true) . 'localhost.key', '/opt/docker/etc/httpd/ssl/server.key')
            ->copy($this->storageDirectory(true) . 'localhost.crt', '/opt/docker/etc/httpd/ssl/server.crt');

        return (string)$command;
    }
}
