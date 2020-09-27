<?php

namespace Sinnbeck\LaravelServed\Images;

class MailhogImage extends Image
{
    /**
     * @var string
     */
    protected $library = 'mailhog';

    /**
     * @var string
     */
    protected $image = 'mailhog';

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
            ->from($this->imageName(), $this->imageTag());

        return (string)$command;
    }
}
