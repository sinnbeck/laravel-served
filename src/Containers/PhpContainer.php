<?php

namespace Sinnbeck\LaravelServed\Containers;

use Illuminate\Support\Arr;

class PhpContainer extends Container
{
    /**
     * @return void
     */
    public function run(): void
    {
        $this->shell->run('docker run -d --restart=always --network="${:network}" --user=served:served --name="${:container_name}" --network-alias=served_php ' . $this->volumes() . ' -v="${:local_dir}":/app "${:image_name}"', $this->env());
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

        return collect($volumes)->map(function ($item) {
            return '-v "' . $item . '"';
        })->implode(' ');
    }
}
