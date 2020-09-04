<?php

namespace Sinnbeck\LaravelServed\Images;

class Apache extends Image
{
    protected $library = 'webdevops';
    protected $image = 'apache';
    protected $tag = 'latest';
    protected $serviceName = 'web';

    protected $buildCommand = 'docker build -t "$imagename" --build-arg uid="$uid" . -f "$dockerfile"';

    protected function prepareConfigFiles()
    {
        $this->copyDockerFile(__DIR__.'/stubs/nginx.conf', 'default.conf');
    }

    protected function prepareEnv()
    {
        return [
            'imagename' => $this->makeImageName(),
            'uid' => getmyuid(),
            'dockerfile' => $this->findDockerFile(),
        ];
    }

    public function generateDockerFile(): string
    {
        $command = $this->dockerFileBuilder
            ->from($this->imageName(), $this->imageTag())
            ->env('WEB_PHP_SOCKET', 'served_php:9000')
            ->env('WEB_DOCUMENT_ROOT', '/var/www/html/public')
            ->env('WEB_PHP_TIMEOUT', '60');

        $this->storeDockerfile($command);
        return (string) $command;

        //http://www.inanzzz.com/index.php/post/su76/creating-apache-mysql-and-php-fpm-containers-for-a-web-application-with-docker-compose
        $command = $this->dockerFileBuilder
            ->from($this->imageName(), $this->tag)
            ->newLine()
            ->copy('vendor/sinnbeck/laravel-served/src/Services/stubs/my-httpd.conf', '/usr/local/apache2/conf/httpd.conf')
            //->env('APACHE_DOCUMENT_ROOT', '/var/www/html')
            ->env('APACHE_RUN_USER', 'served')
            ->env('APACHE_RUN_GROUP', 'served');
            //->run('sed -ri -e \'s!/var/www/html!${APACHE_DOCUMENT_ROOT}!g\' /etc/apache2/sites-available/*.conf')
            //->run('sed -ri -e \'s!/var/www/!${APACHE_DOCUMENT_ROOT}!g\' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf')
            //->comment('enable mod_rewrite for URL rewrite and mod_headers for .htaccess', true)//
            //->run('a2enmod rewrite headers');

        $this->storeDockerfile($command);
        return (string) $command;
    }


}
