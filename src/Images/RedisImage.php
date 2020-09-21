<?php

namespace Sinnbeck\LaravelServed\Images;

class RedisImage extends Image
{
    /**
     * @var string
     */
    protected $image = 'redis';

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
        //
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
            //->copy('redis.conf', '/usr/local/etc/redis/redis.conf')
            //->cmd(['redis-server', '/usr/local/etc/redis/redis.conf']);
            ->cmd(['redis-server']);

        return (string)$command;
    }
}
