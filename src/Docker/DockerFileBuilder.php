<?php

namespace Sinnbeck\LaravelServed\Docker;

class DockerFileBuilder
{
    protected $fileStructure = [];

    public function from(string $image, $tag = null): self
    {
        $this->fileStructure[] = sprintf('FROM %1$s:%2$s', $image, $tag);

        return $this;
    }

    public function newLine(): self
    {
        $this->fileStructure[] = '';

        return $this;
    }

    public function comment(string $comment, $newLineBefore = false): self
    {
        if ($newLineBefore) {
            $this->newLine();
        }

        $this->fileStructure[] = '# '.str_replace("\n", "\n# ", $comment);

        return $this;
    }

    public function arg(string $arg): self
    {
        $this->fileStructure[] = sprintf('ARG %s', $arg);

        return $this;
    }

    public function run($command): self
    {

        if (is_array($command)) {
            $command = \implode(' \\'."\n    && ", $command);
        }

        $this->fileStructure[] = sprintf('RUN %s', $command);

        return $this;
    }

    public function env(string $name, string $value): self
    {
        $this->fileStructure[] = sprintf('ENV %1$s=%2$s', $name, $value);

        return $this;
    }

    public function copy($source, string $target, ?string $from = null): self
    {
        if (is_array($source)) {
            $source = implode(' ', $source);
            $target = rtrim($target, '/').'/';
        }
        $from = $from ? ' --from='.$from : null;
        $this->fileStructure[] = sprintf('COPY%3$s %1$s %2$s', $source, $target, $from);

        return $this;
    }

    public function __toString()
    {
        return implode("\n", $this->fileStructure);
    }
}
