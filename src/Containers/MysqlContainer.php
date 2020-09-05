<?php


namespace Sinnbeck\LaravelServed\Containers;

class MysqlContainer extends Container
{
    protected $port = '3306';
    protected $alias = 'mysql';

    public function run()
    {
        $this->shell->run('docker run -d --restart always --network="$network" --name "$container_name" --network-alias="$alias" -p "$port":3306 -v mysql:/var/lib/mysql/ "$image_name"', $this->env());
    }

    protected function env(): array
    {
        return [
            'network' => $this->projectName(),
            'container_name' => $this->makeContainerName(),
            'image_name' => $this->makeImageName(),
            'port' => $this->port(),
            'alias' => $this->name() . '_ ' . $this->alias,
        ];
    }

}
