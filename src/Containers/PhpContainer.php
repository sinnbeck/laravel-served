<?php

namespace Sinnbeck\LaravelServed\Containers;

use Illuminate\Support\Arr;

class PhpContainer extends Container
{

    public function run()
    {
        $this->shell->run('docker run -d --restart=always --network="${:network}" --user=served:served --name="${:container_name}" --network-alias=served_php '. $this->volumes() . ' -v="${:PWD}":/app "${:image_name}"', $this->env());
    }

    protected function env(): array
    {
        return [
            'network' => $this->projectName(),
            'container_name' => $this->makeContainerName(),
            'image_name' => $this->makeImageName(),
        ];
    }

    protected function volumes()
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
