<?php

namespace Sinnbeck\LaravelServed\Commands;

use Sinnbeck\LaravelServed\Docker\Docker;
use Illuminate\Console\Command;
use Sinnbeck\LaravelServed\Commands\Traits\DockerCheck;

class ServedListCommand extends Command
{
    use DockerCheck;
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
     * @return int
     */
    public function handle(Docker $docker)
    {
        //Done: Check if network exists / create it
        $this->checkPrequisites($docker);
        $containers = $docker->listContainers();

        $this->table($containers->slice(0, 1)->toArray(), $containers->slice(1)->toArray());

        return 0;
    }
}
