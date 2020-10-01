<?php

namespace Sinnbeck\LaravelServed\Commands\Traits;

use Sinnbeck\LaravelServed\Docker\Docker;
use Sinnbeck\LaravelServed\ServiceManager;
use Sinnbeck\LaravelServed\Exceptions\PortAlreadyInUseException;

trait PortCheck
{
    protected function checkPortConflicts(Docker $docker, ServiceManager $manager): void
    {
        $serviceList = $manager->loadServices();

        $servicesWithPort = collect($serviceList)->filter(function($service) {
            return !!$service->container()->port();
        });

        foreach ($servicesWithPort as $service)
        {
            $port = $service->container()->port();
            if ($port == $docker->getUsedPort($service->name())) {
                continue;
            }

            $connection = @fsockopen('localhost', $port);

            if (is_resource($connection))
            {
                fclose($connection);
                throw new PortAlreadyInUseException('Port ' . $port . ' is in use!');
            }
        }
    }
}
