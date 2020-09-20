<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Sinnbeck\LaravelServed\Commands\ServedStartCommand;

class ServedStartCommandTest extends TestCase
{
    /** @test */
    public function it_has_served_start_command()
    {
        $this->assertTrue(class_exists(ServedStartCommand::class));
    }
}