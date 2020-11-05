<?php

namespace Sinnbeck\LaravelServed\Commands;

use Illuminate\Console\Command;
use Sinnbeck\LaravelServed\ServiceManager;
use Sinnbeck\LaravelServed\Commands\Traits\BindOutputToApp;
use Sinnbeck\LaravelServed\Commands\Traits\DockerCheck;
use Sinnbeck\LaravelServed\Commands\Traits\Logo;
use Sinnbeck\LaravelServed\Docker\Docker;
use Symfony\Component\Process\Exception\ProcessFailedException;

class ServedXdebugCommand extends Command
{
    use BindOutputToApp,
        DockerCheck,
        Logo;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'served:xdebug {flag?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enable/disable Xdebug';

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
     * @return int
     */
    public function handle(Docker $docker, ServiceManager $manager): int
    {
        $this->checkPrerequisites($docker);

        $service = $manager->resolveByName('php');

        if (!config( 'served.php.xdebug.enabled', false)) {
            $this->error('You aren\'t using xdebug!');
            return 1;
        }

        $argument = $this->argument('flag');
        if (!$argument) {
            $argument = $this->choice('Do you want to enable or disable?', ['enable', 'disable'], 0);

        }

        if ($argument == 'enable') {
            try {
                $service->enableXdebug();
                $this->info('Xdebug has been enabled!');

            }
            catch (ProcessFailedException $exception) {
                $this->error('Xdebug is already enabled!');
            }

        } elseif ($argument == 'disable') {
            try {
                $service->disableXdebug();
                $this->info('Xdebug has been disabled!');

            }
            catch (ProcessFailedException $exception) {
                $this->error('Xdebug is already disabled!');
            }

        } else {
            $this->warn('Only <info>enable</info> and <error>disable</error> are supported.');
        }

        return 0;
    }
}
