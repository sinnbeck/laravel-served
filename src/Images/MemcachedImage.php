<?php

namespace Sinnbeck\LaravelServed\Images;

class MemcachedImage extends Image
{
    /**
     * @var string
     */
    protected $image = 'memcached';

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
            'dockerfile' => $this->findDockerFile(),
        ];
    }

    /**
     * @return string
     */
    public function writeDockerFile(): string
    {
        $command = $this->dockerFileBuilder
            ->from($this->imageName(), $this->imageTag());

        return (string)$command;
    }
}
