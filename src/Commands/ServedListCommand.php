<?php

namespace Sinnbeck\LaravelServed\Commands;

use Illuminate\Console\Command;
use Sinnbeck\LaravelServed\Commands\Traits\DockerCheck;
use Sinnbeck\LaravelServed\Commands\Traits\Logo;
use Sinnbeck\LaravelServed\Docker\Docker;

class ServedListCommand extends Command
{
    use DockerCheck,
        Logo;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'served:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show containers';

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
    public function handle(Docker $docker): int
    {
        //Done: Check if network exists / create it
        $this->checkPrerequisites($docker);
        $containers = $docker->listContainers();

        $this->drawLogo();
        $this->comment(app('served.name'));
        $this->table($containers->slice(0, 1)->toArray(), $containers->slice(1)->toArray());

        return 0;
    }
}
