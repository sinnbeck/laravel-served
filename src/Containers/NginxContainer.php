<?php

namespace Sinnbeck\LaravelServed\Containers;

class NginxContainer extends Container
{
    /**
     * @var int
     */
    protected $port = 8080;
    protected $internal_port = 80;

    protected $ssl_port = 4443;
    protected $internal_ssl_port = 443;

    protected $dockerRunCommand = '--name "${:container_name}" \
        --network="${:network}" \
        -p="${:port}":80 \
        -p="${:ssl_port}":443 \
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
            'ssl_port' => $this->sslPort(),
            'local_dir' => base_path(),
        ];
    }
}
