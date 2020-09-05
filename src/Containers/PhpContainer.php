<?php

namespace Sinnbeck\LaravelServed\Containers;

class PhpContainer extends Container
{

    public function run()
    {
        $this->shell->run('docker run -d --restart always --network="$network" --user served:served --name "$container_name"  --network-alias=served_php -v "$PWD":/app "$image_name"', $this->env());
    }

    protected function env(): array
    {
        return [
            'network' => $this->projectName(),
            'container_name' => $this->makeContainerName(),
            'image_name' => $this->makeImageName(),
        ];
    }


}
