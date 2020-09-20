<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Sinnbeck\LaravelServed\Commands\ServedTearDownCommand;

class ServedTearDownCommandTest extends TestCase
{
    /** @test */
    public function it_has_served_tear_down_command()
    {
        $this->assertTrue(class_exists(ServedTearDownCommand::class));
    }
}