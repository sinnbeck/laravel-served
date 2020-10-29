<?php

namespace Sinnbeck\LaravelServed\Images;

interface ImageInterface
{
    /**
     * @return void
     */
    public function build($noCache): void;

    /**
     * @return string
     */
    public function writeDockerFile(): string;
}
