<?php

namespace Sinnbeck\LaravelServed\Commands;

use Sinnbeck\LaravelServed\Docker\Docker;
use Illuminate\Console\Command;
use Sinnbeck\LaravelServed\ServiceManager;
use Sinnbeck\LaravelServed\Commands\Traits\DockerCheck;

class ServedStopCommand extends Command
{
    use DockerCheck;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'served:stop {service?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start container(s)';

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
    public function handle(Docker $docker, ServiceManager $manager)
    {
        $this->checkPrequisites($docker);
        $servedName = config('served.name');
        $docker->ensureNetworkExists($servedName);

        $onlyService = $this->argument('service');

        $serviceList = $manager->loadServices();

        foreach ($serviceList as $service) {
            if ($onlyService && $service->name() !== $onlyService) {
                continue;
            }
            $this->info(sprintf('Stopping %s (%s) ...', $service->name(), $service->imageName()));
            $service->container()->stop();

        }

        return 0;

    }
}
