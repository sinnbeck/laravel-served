<?php

namespace Tests\Unit;

use Sinnbeck\LaravelServed\Commands\ServedUpCommand;
use Tests\TestCase;

class ServedUpCommandTest extends TestCase
{
    /** @test */
    public function it_has_served_up_command()
    {
        $this->assertTrue(class_exists(ServedUpCommand::class));
    }
}