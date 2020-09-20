<?php

namespace Tests\Unit;

use Sinnbeck\LaravelServed\Commands\ServedStartCommand;
use Tests\TestCase;

class ServedStartCommandTest extends TestCase
{
    /** @test */
    public function it_has_served_start_command()
    {
        $this->assertTrue(class_exists(ServedStartCommand::class));
    }
}