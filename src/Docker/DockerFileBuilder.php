<?php

namespace Sinnbeck\LaravelServed\Docker;

class DockerFileBuilder
{
    /**
     * @var array
     */
    protected $fileStructure = [];

    /**
     * @param string $image
     * @param null $tag
     * @return $this
     */
    public function from(string $image, $tag = null): self
    {
        $this->fileStructure[] = sprintf('FROM %1$s:%2$s', $image, $tag);

        return $this;
    }

    /**
     * @return $this
     */
    public function newLine(): self
    {
        $this->fileStructure[] = '';

        return $this;
    }

    /**
     * @param string $comment
     * @param false $newLineBefore
     * @return $this
     */
    public function comment(string $comment, $newLineBefore = false): self
    {
        if ($newLineBefore) {
            $this->newLine();
        }

        $this->fileStructure[] = '# ' . str_replace("\n", "\n# ", $comment);

        return $this;
    }

    /**
     * @param string $arg
     * @return $this
     */
    public function arg(string $arg): self
    {
        $this->fileStructure[] = sprintf('ARG %s', $arg);

        return $this;
    }

    /**
     * @param string|array $command
     * @return $this
     */
    public function run($command, $delimiter = '&&'): self
    {
        if (is_array($command)) {
            $command = implode(' \\' . "\n    $delimiter ", $command);
        }

        $this->fileStructure[] = sprintf('RUN %s', $command);

        return $this;
    }

    /**
     * @param string $name
     * @param string $value
     * @return $this
     */
    public function env(string $name, string $value): self
    {
        $this->fileStructure[] = sprintf('ENV %1$s=%2$s', $name, $value);

        return $this;
    }

    /**
     * @param $source
     * @param string $target
     * @param string|null $from
     * @return $this
     */
    public function copy($source, string $target, ?string $from = null): self
    {
        if (is_array($source)) {
            $source = implode(' ', $source);
            $target = rtrim($target, '/') . '/';
        }
        $from = $from ? ' --from=' . $from : null;
        $this->fileStructure[] = sprintf('COPY%3$s %1$s %2$s', $source, $target, $from);

        return $this;
    }

    /**
     * @param string $workdir
     * @return $this
     */
    public function workdir(string $workdir)
    {
        $this->fileStructure[] = sprintf('WORKDIR %s', $workdir);
        return $this;
    }

    /**
     * @param string $workdir
     * @return $this
     */
    public function cmd(array $cmd)
    {
        $items = array_map(function($item) {
            return '"' . $item . '"';
        }, $cmd);

        $this->fileStructure[] = sprintf('CMD [ %s ]', implode(', ', $items));
        return $this;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return implode("\n", $this->fileStructure);
    }
}
