<?php

namespace Tests\Unit;

use Tests\TestCase;
use Sinnbeck\LaravelServed\ServedName;
use Sinnbeck\LaravelServed\Exceptions\InvalidNamingException;
use Mockery;

class NamingTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function it_can_load_set_name()
    {
        $this->app->config->set('served.name', 'my_project');

        $this->assertEquals('my_project', $this->app->get('served.name'));
    }

    /** @test */
    public function it_fails_on_invalid_name()
    {
        $this->app->config->set('served.name', 'bad|name');

        $this->expectException(InvalidNamingException::class);

        $this->app->get('served.name');

    }

    /** @test */
    public function it_fails_on_invalid_name_with_space()
    {
        $this->app->config->set('served.name', 'bad name');

        $this->expectException(InvalidNamingException::class);

        $this->app->get('served.name');

    }

    /** @test */
    public function it_fails_on_invalid_start_character()
    {
        $this->app->config->set('served.name', '_badname');

        $this->expectException(InvalidNamingException::class);

        $this->app->get('served.name');

    }

    /** @test */
    public function it_fails_on_invalid_random_characters()
    {
        $this->app->config->set('served.name', '_bad|dsad|_23');

        $this->expectException(InvalidNamingException::class);

        $this->app->get('served.name');

    }

    /** @test */
    public function it_can_handle_special_characters()
    {
        $nameHandler = Mockery::mock(ServedName::class)->makePartial();

        $nameHandler->shouldReceive('getProjectFolderName')
            ->andReturn('test-s.123|test');

        $this->assertEquals('test_s123test', $nameHandler->projectName());
    }

    /** @test */
    public function it_can_handle_whitespace()
    {
        $nameHandler = Mockery::mock(ServedName::class)->makePartial();

        $nameHandler->shouldReceive('getProjectFolderName')
            ->andReturn('test test');

        $this->assertEquals('test_test', $nameHandler->projectName());
    }

}
