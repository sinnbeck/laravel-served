# Laravel Served

Laravel Served is a dockerized version of `php artisan serve`. It makes it easy to quickly start a development environment the laravel way (config).

The only things you need to get started is
* Php (cli)
* Docker
* Laravel

It is **not** meant to be a replacement for tools like laradock, but is just meant for starting a quick development environment on smaller projects with few special needs.

>Beware: This package is under active development and can major changes can occur at any point. It is therefor a good idea to read the documentation, and republish the config file after each new version.

## Installation
Install the package using composer
```
$ composer require sinnbeck/laravel-served
```

## Running for the first time
It is possible to just start a development server right away after installation. This will bring up 3 docker images:

|Image|Version|Misc|
|-----|-------|------|
|Php-fpm|7.4|Preinstalled with composer, required php modules for running laravel and xdebug running on port 9000 with IDE key 'served'|
|Nginx|1.19|N/A|
|Mysql|5.7|<ul><li>Hostname: mysql</li><li>Database: laravel</li><li>Username: laravel</li><li>Password: password</li></ul>|

To start the containers simply run
```
$ php artisan served:up
```
If this is your first time running the command, it will build the images first before starting the containers. If you have run the `served:up` command before, docker will quickly check the images for updates and served will start the containers again.

>BEWARE: It currently isn't possible having multiple projects running with same base configuration, as that will lead to naming conflicts! If running more than one project on the same machine, please make sure to read the configuration part of the documentation!

## Starting and stopping served
After your first run, you can easily start and stop your containers without having to build either images or containers. Simply run
```
$ php artisan served:start
```
And to stop the containers again without removing anything. 
```
$ php artisan served:stop
```
This is useful to free up ports if you have several projects using the same.

## Ssh
To go into a container to work you can run
```
$ php artisan served:ssh container_name
```
The `container_name` is optional, and will default to php (where you can run artisan etc.).

>Served doesn't actually ssh into the container, but rather just start a bash shell directly. `served:ssh`just sound better and is quick to type.

## Clean up
It is possible to remove all data set up by served. To do this simply run
```
$ php artisan served:teardown
```

## Configuration
While it is possible to just run served without any configuration, it is probably a good idea to configure served for your needs.
To get started you need to publish the config file.
```
$ php artisan vendor:publish --provider="Sinnbeck\LaravelServed\ServedServiceProvider"
```
### Name
To avoid naming conflicts between projects, you can define your own name for served configuration. This name will be used when creating network, images and containers. Make sure it is unique between projects!

It is important that the name only consists of letters, numbers, - and _. Other special characters can result in issues and should be avoided!

If you at some point wish to chance the name after having used served on a project, it is important to teardown both images and containers using `served:teardown`. If you have already changed the name and are having issues getting your containers up and running with the new name, just chance the name back, run teardown, and set it to the new name once more.

### Php
Here you may specify how php should be built. Any options left blank or removed will be defaulted to using the defaults provided by served.

```
'php' => [
        'version' => env('SERVED_PHP_VERSION', '7.4'),
        'modules' => [
            'pdo_mysql',
            'zip',
            'xdebug',
        ],
        'npm' => true,
        'xdebug' => [
            'enabled' => true,
            'port' => 9000,
        ],
    ],
```

The array of modules can be filled with any module found in the url below (except parallel, pthreads and tdlib) 

https://github.com/mlocati/docker-php-extension-installer

### Web
Served currently supports nginx and apache. Simply the type to the servertype you want, and set the correct version (or delete the version to have served use a sensible default). Apache currently only supports the latest version and will ignore any version set.

```
'web' => [
        'service' => 'nginx', //or apache
        'version' => '1.9.2',
        'port' => env('SERVED_WEB_PORT', 8090),
        'https' => [
            'enabled' => false,
        ],
    ],
```

## Extras
Here you can define extra images that you wish to run. The array key is used as name, meaning it is possible to run the same service more than once, with different names (eg. two mysql instances)

The current supported images are:

### Mysql
```
'mysql' => [
            'service' => 'mysql',
            'version' => '5.7',
            'port' => 3306,
            'root_password' => 'password',
            'hostname' => 'mysql', //Must be a string!
            'database' => 'laravel',
            'username' => 'laravel',
            'password' => 'password',
        ],
```
### Postgres
Coming soon

### Redis
Coming soon

## Todo
[] Add more images
[] Find a way to allow user created images
[] Let served make a proper name if none is set (instead of defaulting to 'served')