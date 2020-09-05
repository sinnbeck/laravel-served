<?php

namespace Sinnbeck\LaravelServed\Commands;

use Sinnbeck\LaravelServed\Docker\Docker;
use Illuminate\Console\Command;
use Sinnbeck\LaravelServed\ServiceManager;
use Sinnbeck\LaravelServed\Commands\Traits\DockerCheck;

class ServedUpCommand extends Command
{
    use DockerCheck;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'served:up {service?} {--no-cache}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start webserver and dependencies';

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
        $this->info('Creating network: ' .$servedName);
        $docker->ensureNetworkExists($servedName);

        $onlyService = $this->argument('service');
        $noCache = $this->option('no-cache');
        $serviceList = $manager->loadServices();

        foreach ($serviceList as $service) {
            if ($onlyService && $service->name() !== $onlyService) {
                continue;
            }

            $this->info(sprintf('Building %s (%s) ...', $service->name(), $service->imageName()));
            $service->build($noCache);

        }

        foreach ($serviceList as $service) {
            if ($onlyService && $service->name() !== $onlyService) {
                continue;
            }
            $this->info(sprintf('Starting %s (%s) ...', $service->name(), $service->imageName()));
            $service->run();

        }

        $this->info('Laravel has been served');
        $this->line('Visit the development server at: http://localhost:' . $manager->web()->container()->port());

        return 0;

    }
}
