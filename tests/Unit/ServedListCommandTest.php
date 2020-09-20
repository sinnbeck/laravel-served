<?php

namespace Tests\Unit;

use Sinnbeck\LaravelServed\Commands\ServedListCommand;
use Tests\TestCase;

class ServedListCommandTest extends TestCase
{
    /** @test */
    public function it_has_served_list_command()
    {
        $this->assertTrue(class_exists(ServedListCommand::class));
    }
}