<?php


namespace Sinnbeck\LaravelServed\Images;

class PostgresImage extends Image
{
    /**
     * @var string
     */
    protected $image = 'postgres';

    /**
     * @var string
     */
    protected $tag = '12.4';

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
            ->comment('Setting env vars for postgres init')
            ->env('POSTGRES_DB', 'laravel')
            ->env('POSTGRES_USER', 'laravel')
            ->env('POSTGRES_PASSWORD', 'password');

        return (string)$command;
    }
}
