<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Sinnbeck\LaravelServed\Commands\ServedSshCommand;

class ServedSshCommandTest extends TestCase
{
    /** @test */
    public function it_has_served_ssh_command()
    {
        $this->assertTrue(class_exists(ServedSshCommand::class));
    }
}