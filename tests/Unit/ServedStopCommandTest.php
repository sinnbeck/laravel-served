<?php

namespace Tests\Unit;

use Sinnbeck\LaravelServed\Commands\ServedStopCommand;
use Tests\TestCase;

class ServedStopCommandTest extends TestCase
{
    /** @test */
    public function it_has_served_stop_command()
    {
        $this->assertTrue(class_exists(ServedStopCommand::class));
    }
}