<?php


namespace Sinnbeck\LaravelServed\Images;

class MysqlImage extends Image
{
    /**
     * @var string
     */
    protected $image = 'mysql';

    /**
     * @var string
     */
    protected $tag = '5.7';

    /**
     * @var string
     */
    protected $buildCommand = 'docker build -t "${:imagename}" . -f "${:dockerfile}"';

    /**
     * @return array
     */
    protected function prepareEnv(): array
    {
        return [
            'imagename' => $this->makeImageName(),
            'uid' => getmyuid(),
            'dockerfile' => $this->findDockerFile()
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
            ->comment('Setting env vars for mysql init')
            ->env('MYSQL_ROOT_PASSWORD', 'password')
            ->env('MYSQL_DATABASE', 'laravel')
            ->env('MYSQL_USER', 'laravel')
            ->env('MYSQL_PASSWORD', 'password');

        return (string)$command;
    }
}
