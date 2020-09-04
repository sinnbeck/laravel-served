<?php

namespace Sinnbeck\LaravelServed\Commands;

use Sinnbeck\LaravelServed\Output;
use Sinnbeck\LaravelServed\Services\Php;
use Illuminate\Console\Command;

class ServedSshCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'served:ssh';

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
     * @return int
     */
    public function handle(Php $php)
    {
        $php->container()->ssh();
    }
}
