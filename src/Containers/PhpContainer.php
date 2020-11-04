<?php

namespace Sinnbeck\LaravelServed\Containers;

use Illuminate\Support\Arr;
use Sinnbeck\LaravelServed\Shell\Shell;

class PhpContainer extends Container
{
    protected $dockerRunCommand = '--name "${:container_name}" \
        --user=served:served \
        --network="${:network}" \
        --network-alias=served_php \
        -v="${:local_dir}":/app:cached \
        -v=/app/vendor \
        -v=/app/storage';

    public function __construct(string $name, $config, Shell $shell)
    {
        parent::__construct($name, $config, $shell);
        $this->dockerRunCommand .= $this->volumes();
    }

    /**
     * @return array
     */
    protected function env(): array
    {
        return [
            'network' => $this->projectName(),
            'container_name' => $this->makeContainerName(),
            'image_name' => $this->makeImageName(),
            'local_dir' => base_path(),
        ];
    }

    /**
     * @return string
     */
    protected function volumes(): string
    {
        $volumes = Arr::get($this->config, 'volumes', []);
        if (!$volumes) {
            return '';
        }

        return ' ' . collect($volumes)->map(function ($item) {
            return '-v="' . $item . '"';
        })->implode(' ');
    }
}
