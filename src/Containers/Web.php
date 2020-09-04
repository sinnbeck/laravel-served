<?php

namespace Sinnbeck\LaravelServed\Containers;

class Web extends Container
{
    protected $port = '8080';

    public function run()
    {
        $env = [
            'network' => $this->projectName(),
            'container_name' => $this->makeContainerName(),
            'port' => $this->port,
            'image_name' => $this->makeImageName(),
        ];

        parent::run();
        $this->shell->run('docker run -d --restart always --network="$network" --name "$container_name" -p "$port":80 -v "$PWD":/app "$image_name"', $env);
    }

}
