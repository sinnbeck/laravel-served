<?php

namespace Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use Sinnbeck\LaravelServed\ServedServiceProvider;
use Symfony\Component\Console\Output\OutputInterface;

abstract class TestCase extends BaseTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->mock(OutputInterface::class);
    }

    protected function getExpectedContent($filename)
    {
        return file_get_contents(__DIR__ . '/expected/'  . $filename);
    }

    protected function getPackageProviders($app)
    {
        return [ServedServiceProvider::class];
    }

}
