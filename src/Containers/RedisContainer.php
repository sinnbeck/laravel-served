<?php

namespace Sinnbeck\LaravelServed\Containers;

class RedisContainer extends Container
{

    /**
     * @return void
     */
    public function run(): void
    {
        $this->shell->run('docker run -d --restart always --network="${:network}" --network-alias="${:alias}" --name "${:container_name}" "${:image_name}"', $this->env());
    }

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
