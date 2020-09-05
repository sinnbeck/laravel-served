<?php


namespace Sinnbeck\LaravelServed\Containers;

class Mysql extends Container
{
    protected $port = '3306';
    protected $alias = 'mysql';

    public function run()
    {
        $env = [
            'network' => $this->projectName(),
            'container_name' => $this->makeContainerName(),
            'image_name' => $this->makeImageName(),
            'port' => $this->port,
            'alias' => $this->alias,
        ];

        parent::run();
        $this->shell->run('docker run -d --restart always --network="$network" --name "$container_name" --network-alias="$alias" -p "$port":3306 -v mysql:/var/lib/mysql/ "$image_name"', $env);
    }


}
