<?php

namespace Tests\Unit;

use Tests\TestCase;
use Sinnbeck\LaravelServed\Shell\Shell;
use Sinnbeck\LaravelServed\Containers\PhpContainer;
use Sinnbeck\LaravelServed\Containers\NginxContainer;
use Sinnbeck\LaravelServed\Containers\MysqlContainer;
use Sinnbeck\LaravelServed\Containers\RedisContainer;
use Sinnbeck\LaravelServed\Containers\ApacheContainer;
use Sinnbeck\LaravelServed\Containers\MailhogContainer;
use Sinnbeck\LaravelServed\Containers\PostgresContainer;
use Sinnbeck\LaravelServed\Containers\MemcachedContainer;

class DockerRunTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->app->config->set('served.name', 'served');
        $this->app->setBasePath('/path');
    }

    /** @test */
    public function it_gets_correct_apache_run_command()
    {
        $container = new ApacheContainer('apache_test', [], app(Shell::class));

        $expected = 'docker run -d --restart always --name "${:container_name}" \
        --network="${:network}" \
        -p="${:port}":80 \
        -p="${:ssl_port}":443 \
        -v="${:local_dir}":/app "${:image_name}"';

        $this->assertEquals($expected, $container->getDockerRunCommand());

    }

    /** @test */
    public function it_gets_correct_apache_env()
    {
        $container = new ApacheContainer('apache_test', ['port' => 8090, 'ssl_port' => 4443], app(Shell::class));

        $expected = [
            'network'        => 'served',
            'container_name' => 'served_served_apache_test',
            'image_name'     => 'served/served_apache_test',
            'port'           => 8090,
            'ssl_port'       => 4443,
            'local_dir'      => '/path',

        ];

        $this->assertEquals($expected, $container->getEnv());

    }

    /** @test */
    public function it_gets_correct_nginx_run_command()
    {
        $container = new NginxContainer('nginx_test', [], app(Shell::class));

        $expected = 'docker run -d --restart always --name "${:container_name}" \
        --network="${:network}" \
        -p="${:port}":80 \
        -p="${:ssl_port}":443 \
        -v="${:local_dir}":/app "${:image_name}"';

        $this->assertEquals($expected, $container->getDockerRunCommand());

    }

    /** @test */
    public function it_gets_correct_nginx_env()
    {
        $container = new NginxContainer('nginx_test', ['port' => 8090, 'ssl_port' => 443], app(Shell::class));

        $expected = [
            'network'        => 'served',
            'container_name' => 'served_served_nginx_test',
            'image_name'     => 'served/served_nginx_test',
            'port'           => 8090,
            'ssl_port'       => 443,
            'local_dir'      => '/path',

        ];

        $this->assertEquals($expected, $container->getEnv());

    }

    /** @test */
    public function it_gets_correct_php_run_command()
    {
        $container = new PhpContainer('php_test', ['volumes' => ['/some/local/path:/some/docker/path']], app(Shell::class));

        $expected = 'docker run -d --restart always --name "${:container_name}" \
        --user=served:served \
        --network="${:network}" \
        --network-alias=served_php \
        -v="${:local_dir}":/app -v="/some/local/path:/some/docker/path" "${:image_name}"';

        $this->assertEquals($expected, $container->getDockerRunCommand());

    }

    /** @test */
    public function it_gets_correct_php_env()
    {
        $container = new PhpContainer('php_test', [], app(Shell::class));

        $expected = [
            'network'        => 'served',
            'container_name' => 'served_served_php_test',
            'image_name'     => 'served/served_php_test',
            'local_dir'      => '/path',

        ];

        $this->assertEquals($expected, $container->getEnv());

    }

    /** @test */
    public function it_gets_correct_mysql_run_command()
    {
        $container = new MysqlContainer('mysql_test', [], app(Shell::class));

        $expected = 'docker run -d --restart always --name "${:container_name}" \
        --network="${:network}" \
        --network-alias="${:alias}" \
        -p="${:port}":3306 \
        -v="${:volume}":/var/lib/mysql/ "${:image_name}"';

        $this->assertEquals($expected, $container->getDockerRunCommand());

    }

    /** @test */
    public function it_gets_correct_mysql_env()
    {
        $container = new MysqlContainer('mysql_test', ['port' => 3212], app(Shell::class));

        $expected = [
            'network'        => 'served',
            'container_name' => 'served_served_mysql_test',
            'image_name'     => 'served/served_mysql_test',
            'port'           => 3212,
            'alias'          => 'mysql_test',
            'volume'         => 'served_mysql_test',

        ];

        $this->assertEquals($expected, $container->getEnv());

    }

    /** @test */
    public function it_gets_correct_postgres_run_command()
    {
        $container = new PostgresContainer('postgres_test', [], app(Shell::class));

        $expected = 'docker run -d --restart always --name "${:container_name}" \
        --network="${:network}" \
        --network-alias="${:alias}" \
        -p="${:port}":5432 \
        -v="${:volume}":/var/lib/postgresql/data "${:image_name}"';

        $this->assertEquals($expected, $container->getDockerRunCommand());

    }

    /** @test */
    public function it_gets_correct_postgres_env()
    {
        $container = new PostgresContainer('postgres_test', ['port' => 3212], app(Shell::class));

        $expected = [
            'network'        => 'served',
            'container_name' => 'served_served_postgres_test',
            'image_name'     => 'served/served_postgres_test',
            'port'           => 3212,
            'alias'          => 'postgres_test',
            'volume'         => 'served_postgres_test',

        ];

        $this->assertEquals($expected, $container->getEnv());

    }


    /** @test */
    public function it_gets_correct_redis_run_command()
    {
        $container = new RedisContainer('redis_test', [], app(Shell::class));

        $expected = 'docker run -d --restart always --name "${:container_name}" \
        --network="${:network}" \
        --network-alias="${:alias}" "${:image_name}"';

        $this->assertEquals($expected, $container->getDockerRunCommand());

    }

    /** @test */
    public function it_gets_correct_redis_env()
    {
        $container = new RedisContainer('redis_test', [], app(Shell::class));

        $expected = [
            'network'        => 'served',
            'container_name' => 'served_served_redis_test',
            'image_name'     => 'served/served_redis_test',
            'alias'          => 'redis_test',

        ];

        $this->assertEquals($expected, $container->getEnv());

    }

    /** @test */
    public function it_gets_correct_memcached_run_command()
    {
        $container = new MemcachedContainer('memcached_test', [], app(Shell::class));

        $expected = 'docker run -d --restart always --name "${:container_name}" \
        --network="${:network}" \
        --network-alias="${:alias}" "${:image_name}"';

        $this->assertEquals($expected, $container->getDockerRunCommand());

    }

    /** @test */
    public function it_gets_correct_memcached_env()
    {
        $container = new MemcachedContainer('memcached_test', [], app(Shell::class));

        $expected = [
            'network'        => 'served',
            'container_name' => 'served_served_memcached_test',
            'image_name'     => 'served/served_memcached_test',
            'alias'          => 'memcached_test',

        ];

        $this->assertEquals($expected, $container->getEnv());

    }

    /** @test */
    public function it_gets_correct_mailhog_run_command()
    {
        $container = new MailhogContainer('mailhog_test', [], app(Shell::class));

        $expected = 'docker run -d --restart always --name "${:container_name}" \
        --network="${:network}" \
        --network-alias="${:alias}" \
        -p "${:port}":8025 "${:image_name}"';

        $this->assertEquals($expected, $container->getDockerRunCommand());

    }

    /** @test */
    public function it_gets_correct_mailhog_env()
    {
        $container = new RedisContainer('mailhog_test', [], app(Shell::class));

        $expected = [
            'network'        => 'served',
            'container_name' => 'served_served_mailhog_test',
            'image_name'     => 'served/served_mailhog_test',
            'alias'          => 'mailhog_test',

        ];

        $this->assertEquals($expected, $container->getEnv());

    }

}
