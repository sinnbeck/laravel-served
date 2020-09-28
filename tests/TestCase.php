<?php

namespace Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use Sinnbeck\LaravelServed\ServedServiceProvider;

abstract class TestCase extends BaseTestCase
{
    public function setUp(): void
    {
        parent::setUp();
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
