<?php

namespace Sinnbeck\LaravelServed\Commands;

use Sinnbeck\LaravelServed\Docker\Docker;
use Illuminate\Console\Command;
use Sinnbeck\LaravelServed\Services\Services;
use Sinnbeck\LaravelServed\Docker\DockerFileBuilder;
use Sinnbeck\LaravelServed\Commands\Traits\DockerCheck;

class ServedTearDownCommand extends Command
{
    use DockerCheck;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'served:teardown {service?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove containers, images and network';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(Docker $docker, Services $services)
    {
        //Done: Check if network exists / create it
        $this->checkPrequisites($docker);
        $servedName = config('served.name');

        $onlyService = $this->argument('service');

        $serviceList = $services->prepareServiceList();

        foreach ($serviceList as $service) {
            if ($onlyService && $service->simpleName() !== $onlyService) {
                continue;
            }

            $this->info(sprintf('Removing container for %s (%s) ...', $service->name(), $service->image()->imageTag()));

            $service->container()->remove();

        }

        foreach ($serviceList as $service) {
            if ($onlyService && $service->simpleName() !== $onlyService) {
                continue;
            }

            $this->info(sprintf('Removing image for %s (%s) ...', $service->name(), $service->image()->imageTag()));

            $service->image()->remove();

        }

        if (!$onlyService) {
            $docker->removeNetwork($servedName);
        }

        return 0;
    }
}
