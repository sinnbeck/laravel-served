<?php

namespace Sinnbeck\LaravelServed\Commands\Traits;

use Sinnbeck\LaravelServed\Docker\Docker;
use Sinnbeck\LaravelServed\ServiceManager;
use Sinnbeck\LaravelServed\Services\Service;
use Sinnbeck\LaravelServed\Exceptions\PortAlreadyInUseException;

trait PortCheck
{
    protected function checkPortConflicts(Docker $docker, ServiceManager $manager): void
    {
        $serviceList = $manager->loadServices();

        $servicesWithPort = collect($serviceList)->filter(function($service) {
            return !!$service->container()->port();
        });

        foreach ($servicesWithPort as $service) {
            $port = $service->container()->port();
            $internalPort = $service->container()->internalPort();
            $sslPort = $service->container()->sslPort();
            $internalSslPort = $service->container()->internalSslPort();
            if ($this->testPort($docker, $service, $port, $internalPort) && $this->testPort($docker, $service, $sslPort, $internalSslPort)) {
                continue;
            }
        }
    }

    protected function testPort(Docker $docker, Service $service, ?int $port = null, ?int $internalPort = null): bool
    {
        if (!$port) {
            return true;
        }

        if ($port == $docker->getUsedPort($service->name(), $internalPort)) {
            return true;
        }

        $connection = @fsockopen('localhost', $port);

        if (is_resource($connection))
        {
            fclose($connection);
            throw new PortAlreadyInUseException('Port ' . $port . ' is in use!');
        }

        return true;
    }
}
