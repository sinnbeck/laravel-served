<?php

namespace Sinnbeck\LaravelServed\Commands;

use Exception;
use Illuminate\Console\Command;
use Sinnbeck\LaravelServed\Commands\Traits\DockerCheck;
use Sinnbeck\LaravelServed\Docker\Docker;
use Sinnbeck\LaravelServed\ServiceManager;

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
     * @param Docker $docker
     * @param ServiceManager $manager
     * @return int
     * @throws Exception
     */
    public function handle(Docker $docker, ServiceManager $manager): int
    {
        //Done: Check if network exists / create it
        $this->checkPrerequisites($docker);
        $servedName = app('served.name');

        $onlyService = $this->argument('service');

        $serviceList = $manager->loadServices();

        foreach ($serviceList as $service) {
            if ($onlyService && $service->name() !== $onlyService) {
                continue;
            }

            $this->info(sprintf('Removing container for %s (%s) ...', $service->name(), $service->imageName()));

            $service->container()->remove();

        }

        foreach ($serviceList as $service) {
            if ($onlyService && $service->name() !== $onlyService) {
                continue;
            }

            $this->info(sprintf('Removing image for %s (%s) ...', $service->name(), $service->imageName()));

            $service->image()->remove();

        }

        if (!$onlyService) {
            $this->info(sprintf('Removing network %s ...', $servedName));
            $docker->removeNetwork($servedName);
        }

        return 0;
    }
}
