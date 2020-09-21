<?php

namespace Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;

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

}
