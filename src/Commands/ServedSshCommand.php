<?php

namespace Sinnbeck\LaravelServed\Commands;

use Exception;
use Illuminate\Console\Command;
use Sinnbeck\LaravelServed\Exceptions\TtyNotSupportedException;
use Sinnbeck\LaravelServed\ServiceManager;

class ServedSshCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'served:ssh {service=php}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enter the php container (not really ssh!)';

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
     * @param ServiceManager $manager
     * @return int
     * @throws Exception
     */
    public function handle(ServiceManager $manager): int
    {
        $serviceName = $this->argument('service');
        $service = $manager->resolveByName($serviceName);

        try {
            $service->container()->ssh();

        } catch (TtyNotSupportedException $e) {
            $this->error('Your platform does not support TTY. This means that artisan cannot handle the docker shell.');
            $this->line(sprintf('Instead run <fg=green>%s</> manually', $service->container()->fallbackSsh()));
        }

        return 0;
    }
}
