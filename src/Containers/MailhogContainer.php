<?php

namespace Sinnbeck\LaravelServed\Containers;

class MailhogContainer extends Container
{
    /**
     * @var string
     */
    protected $port = '8025';

    /**
     * @return void
     */
    public function run(): void
    {
        $this->shell->run('docker run -d --restart always --network="${:network}" --network-alias="${:alias}" -p="${:port}":8025 --name "${:container_name}" "${:image_name}"', $this->env());
    }

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
