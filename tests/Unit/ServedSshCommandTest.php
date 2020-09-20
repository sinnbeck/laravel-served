<?php

namespace Tests\Unit;

use Sinnbeck\LaravelServed\Commands\ServedSshCommand;
use Tests\TestCase;

class ServedSshCommandTest extends TestCase
{
    /** @test */
    public function it_has_served_ssh_command()
    {
        $this->assertTrue(class_exists(ServedSshCommand::class));
    }
}