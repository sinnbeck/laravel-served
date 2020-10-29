<?php

namespace Sinnbeck\LaravelServed\Images;

class NginxImage extends Image
{
    /**
     * @var string
     */
    protected $image = 'nginx';

    /**
     * @var string
     */
    protected $tag = '1.19';

    /**
     * @var string
     */
    protected $buildCommand = 'docker build -t "${:imagename}" . -f "${:dockerfile}"';

    /**
     * @return void
     */
    protected function prepareConfFiles(): void
    {
        $this->copyDockerFile(__DIR__ . '/stubs/nginx.conf', 'default.conf');
        $this->copyDockerFile(__DIR__ . '/stubs/localhost.crt', 'localhost.crt');
        $this->copyDockerFile(__DIR__ . '/stubs/localhost.key', 'localhost.key');
    }

    /**
     * @return array
     */
    protected function prepareEnv()
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
        $command = $this->getBaseDockerFile()
            ->newLine()
            ->comment('Copy in new nginx config')
//            ->copy('storage/served/nginx/default.conf', '/etc/nginx/conf.d/default.conf');
            ->copy($this->storageDirectory(true) . 'default.conf', '/etc/nginx/conf.d/default.conf')
            ->copy($this->storageDirectory(true) . 'localhost.key', '/etc/nginx/ssl/server.key')
            ->copy($this->storageDirectory(true) . 'localhost.crt', '/etc/nginx/ssl/server.crt');

        return (string)$command;
    }
}
