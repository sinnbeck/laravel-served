<?php


namespace Sinnbeck\LaravelServed\Commands\Traits;


use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

trait BindOutputToApp
{

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        parent::initialize($input, $output);
        $this->bindOutput($output);
    }

    /**
     * bind output to dependency container
     * @param OutputInterface $output
     */
    private function bindOutput(OutputInterface $output)
    {
        app()->singleton(OutputInterface::class, function() use ($output) {
            return $output;
        });
    }
}
