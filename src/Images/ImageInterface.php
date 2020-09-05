<?php

namespace Sinnbeck\LaravelServed\Images;

interface ImageInterface
{
    public function build(): void;

    public function writeDockerFile(): string;
}
