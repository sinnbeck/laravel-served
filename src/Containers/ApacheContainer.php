<?php

namespace Sinnbeck\LaravelServed\Containers;

class ApacheContainer extends Container
{
    protected $port = '8080';

    public function run()
    {
        $this->shell->run('docker run -d --restart always --network="${:network}" --name "${:container_name}" -p "${:port}":80 -v "${:PWD}":/app "${:image_name}"', $this->env());
    }

    protected function env(): array
    {
        return [
            'network' => $this->projectName(),
            'container_name' => $this->makeContainerName(),
            'image_name' => $this->makeImageName(),
            'port' => $this->port(),
        ];

    }
}
