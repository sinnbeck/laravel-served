<?php

namespace Sinnbeck\LaravelServed\Containers;

class MemcachedContainer extends Container
{
    protected $dockerRunCommand = '--name "${:container_name}" \
        --network="${:network}" \
        --network-alias="${:alias}"';

    /**
     * @return array
     */
    protected function env(): array
    {
        return [
            'network' => $this->projectName(),
            'alias' => $this->name(),
            'container_name' => $this->makeContainerName(),
            'image_name' => $this->makeImageName(),
        ];
    }
}
