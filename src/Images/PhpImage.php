<?php

namespace Sinnbeck\LaravelServed\Images;

use Illuminate\Support\Arr;

class PhpImage extends Image
{
    /**
     * @var string
     */
    protected $image = 'php';

    /**
     * @var string
     */
    protected $tag = '7.4';

    /**
     * @var string
     */
    protected $tagAddition = '-fpm';

    /**
     * @var string
     */
    protected $buildCommand = 'docker build -t "${:imagename}" --build-arg uid="${:uid}" . -f "${:dockerfile}"';

    /**
     * @return array
     */
    protected function prepareEnv(): array
    {
        return [
            'imagename' => $this->makeImageName(),
            'uid' => getmyuid() <= 1 ? 1000 : getmyuid(),
            'dockerfile' => $this->findDockerFile(),
        ];
    }

    /**
     * @return string
     */
    public function writeDockerFile(): string
    {
        $runInstalls = [
            'apt-get update',
            'apt-get install -y unzip zip gnupg',
            'rm -rf /var/lib/apt/lists/*',
        ];


        $command = $this->dockerFileBuilder->from($this->imageName(), $this->imageTag())
            ->comment('disable warnings for "dangerous" messages', true)
            ->env('APT_KEY_DONT_WARN_ON_DANGEROUS_USAGE', '1')
            ->comment('Adding linux packages', true)
            ->run($runInstalls);

        $command
            ->comment('Installing packages for sql dump')
            ->run([
                'RUN set -ex;',
                'key=\'A4A9406876FCBD3C456770C88C718D3B5072E1F5\';',
                'export GNUPGHOME="$(mktemp -d)";',
                'gpg --batch --keyserver ha.pool.sks-keyservers.net --recv-keys "$key";',
                'gpg --batch --export "$key" > /etc/apt/trusted.gpg.d/mysql.gpg;',
                'gpgconf --kill all;',
                'rm -rf "$GNUPGHOME";',
                'apt-key list > /dev/null',
            ], '')
            ->newLine()
            ->run([
                'echo "deb http://repo.mysql.com/apt/debian/ buster mysql-8.0" > /etc/apt/sources.list.d/mysql.list',
                'apt-get update',
                'apt-get install -y mysql-community-client postgresql-client sqlite3',
                'rm -rf /var/lib/apt/lists/*'
            ]);

        $command
            ->comment('add development php.ini file', true)
            ->run('mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"');

        $command
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

        if (Arr::get($this->config, 'npm', false)) {
            $command
                ->comment('Adding NPM')
                ->run([
                'curl -sL https://deb.nodesource.com/setup_12.x | bash',
                'apt-get install -y nodejs',
                'curl -L https://www.npmjs.com/install.sh | sh',
            ]);
        }

        $modules = Arr::get($this->config, 'modules', []);

        if (Arr::get($this->config, 'xdebug.enabled') && !in_array('xdebug', $modules)) {
            $modules[] = 'xdebug';
        }

        if ($modules) {
            $command
                ->comment('Adding php packages', true)
                ->copy('/usr/bin/install-php-extensions', '/usr/bin/', 'mlocati/php-extension-installer')
                ->run('install-php-extensions ' . implode(' ', $modules));

        }

        if (in_array('xdebug', $modules) && Arr::get($this->config, 'xdebug.enabled')) {
            $command
                ->comment('Adding xdebug', true)
                ->run([
                    'echo "[xdebug]" >> "$PHP_INI_DIR/conf.d/docker-php-ext-xdebug.ini"',
                    'echo "xdebug.remote_enable = 1" >> "$PHP_INI_DIR/conf.d/docker-php-ext-xdebug.ini"',
                    'echo "xdebug.remote_port = ' . Arr::get($this->config, 'xdebug.port', 9001) . '" >> "$PHP_INI_DIR/conf.d/docker-php-ext-xdebug.ini"',
                    'echo "xdebug.remote_connect_back = 1" >> "$PHP_INI_DIR/conf.d/docker-php-ext-xdebug.ini"',
                    'echo "xdebug.remote_autostart = 1" >> "$PHP_INI_DIR/conf.d/docker-php-ext-xdebug.ini"',
                ]);
        }

        $command
            ->comment('Set work dir', true)
            ->workdir('/app');

        return (string)$command;
    }
}
