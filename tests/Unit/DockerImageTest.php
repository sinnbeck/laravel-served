<?php

namespace Tests\Unit;

use Tests\TestCase;
use Sinnbeck\LaravelServed\Shell\Shell;
use Sinnbeck\LaravelServed\Images\PhpImage;
use Sinnbeck\LaravelServed\Images\NginxImage;
use Sinnbeck\LaravelServed\Images\MysqlImage;
use Sinnbeck\LaravelServed\Images\RedisImage;
use Sinnbeck\LaravelServed\Images\ApacheImage;
use Sinnbeck\LaravelServed\Images\MailhogImage;

class DockerImageTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function it_can_make_apache_docker_file()
    {
        $image = new ApacheImage('test', [], app(Shell::class));
        $content = $image->writeDockerFile();

        $this->assertEquals($this->getExpectedContent('DockerFile-apache'), $content);
    }

    /** @test */
    public function it_can_make_nginx_docker_file()
    {
        $image = new NginxImage('test', [], app(Shell::class));
        $content = $image->writeDockerFile();

        $this->assertEquals($this->getExpectedContent('DockerFile-nginx'), $content);
    }

    /** @test */
    public function it_can_make_php_docker_file()
    {
        $image = new PhpImage('test', [], app(Shell::class));
        $content = $image->writeDockerFile();

        $this->assertEquals($this->getExpectedContent('DockerFile-php'), $content);
    }

    /** @test */
    public function it_can_make_mysql_docker_file()
    {
        $image = new MysqlImage('test', [], app(Shell::class));
        $content = $image->writeDockerFile();

        $this->assertEquals($this->getExpectedContent('DockerFile-mysql'), $content);
    }

    /** @test */
    public function it_can_make_postgres_docker_file()
    {
        $image = new RedisImage('test', [], app(Shell::class));
        $content = $image->writeDockerFile();

        $this->assertEquals($this->getExpectedContent('DockerFile-redis'), $content);
    }

    /** @test */
    public function it_can_make_redis_docker_file()
    {
        $image = new RedisImage('test', [], app(Shell::class));
        $content = $image->writeDockerFile();

        $this->assertEquals($this->getExpectedContent('DockerFile-redis'), $content);
    }

    /** @test */
    public function it_can_make_mailhog_docker_file()
    {
        $image = new MailhogImage('test', [], app(Shell::class));
        $content = $image->writeDockerFile();

        $this->assertEquals($this->getExpectedContent('DockerFile-mailhog'), $content);
    }


}
