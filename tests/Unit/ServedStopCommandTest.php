<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Sinnbeck\LaravelServed\Commands\ServedStopCommand;

class ServedStopCommandTest extends TestCase
{
    /** @test */
    public function it_has_served_stop_command()
    {
        $this->assertTrue(class_exists(ServedStopCommand::class));
    }
}