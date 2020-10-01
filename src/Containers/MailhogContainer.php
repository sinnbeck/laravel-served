<?php

namespace Sinnbeck\LaravelServed\Containers;

class MailhogContainer extends Container
{
    /**
     * @var string
     */
    protected $port = '8025';
    protected $internal_port = '8025';

    protected $dockerRunCommand = '--name "${:container_name}" \
        --network="${:network}" \
        --network-alias="${:alias}" \
        -p "${:port}":8025';

    /**
     * @return array
     */
    protected function env(): array
    {
        return [
            'network' => $this->projectName(),
            'alias' => $this->name(),
            'port' => $this->port(),
            'container_name' => $this->makeContainerName(),
            'image_name' => $this->makeImageName(),
        ];
    }
}
