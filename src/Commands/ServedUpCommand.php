<?php

namespace Sinnbeck\LaravelServed\Commands;

use Exception;
use Illuminate\Console\Command;
use Sinnbeck\LaravelServed\Commands\Traits\BindOutputToApp;
use Sinnbeck\LaravelServed\Commands\Traits\PortCheck;
use Sinnbeck\LaravelServed\Commands\Traits\DockerCheck;
use Sinnbeck\LaravelServed\Commands\Traits\RunningConfig;
use Sinnbeck\LaravelServed\Docker\Docker;
use Sinnbeck\LaravelServed\ServiceManager;
use Sinnbeck\LaravelServed\Exceptions\PortAlreadyInUseException;
use Sinnbeck\LaravelServed\Exceptions\ShellCommandFailedException;

class ServedUpCommand extends Command
{
    use BindOutputToApp,
        DockerCheck,
        PortCheck,
        RunningConfig;

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
     * @param Docker $docker
     * @param ServiceManager $manager
     * @return int
     * @throws Exception
     */
    public function handle(Docker $docker, ServiceManager $manager): int
    {
        $this->checkPrerequisites($docker);
        try {
            $this->checkPortConflicts($docker, $manager);

        }
        catch (PortAlreadyInUseException $e) {
            $this->error($e->getMessage());
            return 1;
        }

        $servedName = app('served.name');
        $this->info('Creating network: ' . $servedName);
        $docker->ensureNetworkExists($servedName);

        $onlyService = $this->argument('service');
        $noCache = $this->option('no-cache');
        $serviceList = $manager->loadServices();

        try {
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

        } catch (ShellCommandFailedException $exception) {
            $this->error($exception->getMessage());
            return 1;
        }

        $this->servedRunning($manager);

        return 0;
    }
}
