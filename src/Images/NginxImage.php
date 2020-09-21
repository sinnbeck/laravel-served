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
        $command = $this->dockerFileBuilder
            ->from($this->imageName(), $this->imageTag())
            ->newLine()
            ->comment('Copy in new nginx config')
//            ->copy('storage/served/nginx/default.conf', '/etc/nginx/conf.d/default.conf');
            ->copy($this->storageDirectory(true) . 'default.conf', '/etc/nginx/conf.d/default.conf');

        return (string)$command;
    }
}
