<?php

namespace Sinnbeck\LaravelServed\Images;

interface ImageInterface
{
    /**
     * @return void
     */
    public function build(): void;

    /**
     * @return string
     */
    public function writeDockerFile(): string;
}
