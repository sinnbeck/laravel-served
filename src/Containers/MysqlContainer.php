<?php


namespace Sinnbeck\LaravelServed\Containers;

use Illuminate\Support\Arr;

class MysqlContainer extends Container
{
    protected $port = '3306';
    protected $alias = 'mysql';

    public function run()
    {
        $this->shell->run('docker run -d --restart always --network="$network" --name "$container_name" --network-alias="$alias" -p "$port":3306 -v "$volume":/var/lib/mysql/ "$image_name"', $this->env());
    }

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

    protected function volume()
    {
        if ($volume = Arr::get($this->config, 'volume')) {
            return $volume;
        }

        return $this->projectName() . '_' . $this->name();
    }

}
