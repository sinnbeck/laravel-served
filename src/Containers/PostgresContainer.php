<?php


namespace Sinnbeck\LaravelServed\Containers;

use Illuminate\Support\Arr;

class PostgresContainer extends Container
{
    /**
     * @var string
     */
    protected $port = '54320';
    protected $internal_port = '5432';

    /**
     * @var string
     */
    protected $alias = 'postgres';

    protected $dockerRunCommand = '--name "${:container_name}" \
        --network="${:network}" \
        --network-alias="${:alias}" \
        -p="${:port}":5432 \
        -v="${:volume}":/var/lib/postgresql/data';

    /**
     * @return array
     */
    protected function env(): array
    {
        return [
            'network' => $this->projectName(),
            'container_name' => $this->makeContainerName(),
            'image_name' => $this->makeImageName(),
            'port' => $this->port(),
            'alias' => $this->name(),
            'volume' => $this->volume(),
        ];
    }

    /**
     * @return string
     */
    protected function volume(): string
    {
        if ($volume = Arr::get($this->config, 'volume')) {
            return $volume;
        }

        return $this->projectName() . '_' . $this->name();
    }
}
