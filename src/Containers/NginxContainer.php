<?php

namespace Sinnbeck\LaravelServed\Containers;

class NginxContainer extends Container
{
    /**
     * @var string
     */
    protected $port = '8080';

    /**
     * @return void
     */
    public function run(): void
    {
        $this->shell->run('docker run -d --restart always --network="${:network}" --name "${:container_name}" -p "${:port}":80 -v "${:local_dir}":/app "${:image_name}"', $this->env());
    }

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
