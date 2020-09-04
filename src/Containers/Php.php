<?php

namespace Sinnbeck\LaravelServed\Containers;

class Php extends Container
{
    
    public function run()
    {
        $env = [
            'network' => $this->projectName(),
            'container_name' => $this->makeContainerName(),
            'image_name' => $this->makeImageName(),
        ];

        parent::run();
        $this->shell->run('docker run -d --restart always --network="$network" --user served:served --name "$container_name"  --network-alias=served_php -v "$PWD":/var/www/html "$image_name"', $env);
    }


}
