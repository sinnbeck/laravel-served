<?php

namespace Sinnbeck\LaravelServed\Images;

class Php extends Image
{
    protected $image = 'php';
    protected $tag = '7.4';
    protected $tagAddition = '-fpm';
    protected $modules = [];
    protected $buildCommand = 'docker build -t "$imagename" --build-arg uid="$uid" . -f "$dockerfile"';
    protected $serviceName = 'php';

    protected function prepareEnv()
    {
        return [
            'imagename' => $this->makeImageName(),
            'uid' => getmyuid() == 1 ? 1000 : getmyuid(),
            'dockerfile' => $this->findDockerFile(),
        ];
    }

    public function generateDockerFile(): string
    {
        $command = $this->dockerFileBuilder->from($this->imageName(), $this->imageTag())
            ->comment('disable warnings for "dangerous" messages', true)
            ->env('APT_KEY_DONT_WARN_ON_DANGEROUS_USAGE', '1')
            ->comment('Adding linux packages', true)
            ->run([
                'apt-get update',
                'apt-get install -y unzip zip',
                'rm -rf /var/lib/apt/lists/*',
            ]);

        if (config('served.php.npm')) {
            $command
                ->run([
                    'curl -sL https://deb.nodesource.com/setup_12.x | bash',
                    'apt-get install -y nodejs',
                    'curl -L https://www.npmjs.com/install.sh | sh'
                ]);
        }

        $modules = $this->modules;

        if (config('served.php.xdebug.enabled') && !in_array('xdebug', $modules)) {
            $modules[] = 'xdebug';
        }

        if ($modules) {
            $command
                ->comment('Adding php packages', true)
                ->copy('/usr/bin/install-php-extensions', '/usr/bin/', 'mlocati/php-extension-installer')
                ->run('install-php-extensions '. implode(' ', $modules));


        }

        if (in_array('xdebug', $modules)) {
            $command
                ->comment('Adding xdebug', true)
                ->run([
                    'echo "[xdebug]" >> "$PHP_INI_DIR/conf.d/docker-php-ext-xdebug.ini"',
                    //                        'echo "xdebug.profiler_enable_trigger = 1" >> "$PHP_INI_DIR/conf.d/docker-php-ext-xdebug.ini"',
                    'echo "xdebug.remote_enable = 1" >> "$PHP_INI_DIR/conf.d/docker-php-ext-xdebug.ini"',
                    'echo "xdebug.remote_port = ' . config('served.php.xdebug.port', 9000) . '" >> "$PHP_INI_DIR/conf.d/docker-php-ext-xdebug.ini"',
                    'echo "xdebug.remote_connect_back = 1" >> "$PHP_INI_DIR/conf.d/docker-php-ext-xdebug.ini"',
                    'echo "xdebug.remote_autostart = 1" >> "$PHP_INI_DIR/conf.d/docker-php-ext-xdebug.ini"',
                    //                        'echo "xdebug.trace_enable_trigger = 1" >> "$PHP_INI_DIR/conf.d/docker-php-ext-xdebug.ini"',
                ]);
        }

        $command
            ->comment('add development php.ini file', true)
            ->run('mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"')
            ->comment("add a local user with the same uid as the local\nprepare empty composer config directory\nensure user owns its home directory")
            ->arg('uid')
            ->run([
                'useradd -G root -u $uid -d /home/served served',
                'mkdir -p /home/served/.composer',
                'chown -R served:served /home/served'
            ])
            ->comment('set new user to run php-fpm')
            ->comment("add composer\nset composer to use https\nadd prestissimo run composer in parallel", true)
            ->run([
                'curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer',
                'runuser -l served -c "composer config --global repos.packagist composer https://packagist.org"',
                'runuser -l served -c "composer global require hirak/prestissimo"'
            ]);

        $this->storeDockerfile($command);

        return (string) $command;
    }

    public function setModules(array $modules)
    {
        $this->modules = $modules;
    }
}
