<?php

namespace Sinnbeck\LaravelServed\Containers;

class NginxContainer extends Container
{
    /**
     * @var string
     */
    protected $port = '8080';

    protected $dockerRunCommand = '--name "${:container_name}" \
        --network="${:network}" \
        -p="${:port}":80 \
        -v="${:local_dir}":/app';

    /**
     * @return array
     */
    public function env(): array
    {
        return [
            'network' => $this->projectName(),
            'container_name' => $this->makeContainerName(),
            'image_name' => $this->makeImageName(),
            'port' => $this->port(),
            'local_dir' => base_path(),
        ];
    }
}
