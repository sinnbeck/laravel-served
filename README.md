# Laravel Served

Laravel Served is a dockerized version of `php artisan serve`. It makes it easy to quickly start a development environment the laravel way (through a config file).

The only things you need to get started is
* Php (cli)
* Docker
* Laravel

>Beware: This package is under active development and major changes can occur at any point. It is therefore a good idea to read the documentation, and republish the config file after each new version.

## Available services
These are the available services that Served provide. More will be added in the future. If you are missing something specific, just create a new issue, requesting it.
* Php
* Nginx
* Apache2
* Mysql
* Postgres
* Redis
* Mailhog

## Installation
Install the package using composer
```
$ composer require sinnbeck/laravel-served --dev
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
        ],
        'npm' => true, //enable or disable npm in build
        'xdebug' => [
            'enabled' => true,
            'port' => 9001,
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
Here you can define extra images that you wish to run. The array key is used as name, meaning it is possible to run the same service more than once, with different names (eg. two mysql instances).

The current supported images are:

### Mysql
Port is used for when connecting to mysql from outside of laravel. 
Eg. 127.0.0.1:3306. 

To connect to the database from laravel you need to use the config key (in the example that would be `mysql`) as hostname. The port is the default for mysql (3306) and not the one specified in the config.
```
'mysql' => [
            'service' => 'mysql',
            'version' => '5.7',
            'port' => 3306,
            'root_password' => 'password',
            'database' => 'laravel',
            'username' => 'laravel',
            'password' => 'password',
        ],
```

### Postgres
To connect to postgresql from laravel you need to use the config key (in the example that would be `postgres`) as hostname. The port is the default for mysql (5432) and not the one specified in the config. To connect from outside of laravel, use the port specified in the config (eg. 54320) and 127.0.0.1
```
'postgres' => [
            'service' => 'postgres',
            'version' => '12.4',
            'port' => 54320,
            'database' => 'laravel',
            'username' => 'laravel',
            'password' => 'password',
        ],
```

### Redis
Add redis to the modules in php and then add redis to your extras array.
```
'redis' => [
            'service' => 'redis',
        ]
```
Change your `REDIS_HOST` in .env to whatever you use as the key (eg. redis)

### Mailhog
Add mailhog to your extras array.
```
'mail' => [
            'service' => 'mailhog',
            'port' => 8025
        ]
```
Change your `MAIL_HOST` in .env to whatever you use as the key (eg. mail), and change `MAIL_PORT`to 1025. To see the mailbox, open http://localhost:8025 in your browser (replace 8025 with whatever port you set in config)

## Testing
To run tests with 
```
$ composer test
```


## Todo
- [ ] Testing!
- [ ] Add more images
- [ ] Allow user created services
- [ ] Let served make a proper name if none is set (instead of defaulting to 'served')
- [ ] Handle setting/adding volumes
- [ ] Handle removal of volumes
- [ ] Handle upgrades/downgrades of images
- [ ] Pass cli output interface to other classes to allow outputting to cli from them
- [x] Test on other platforms than linux (Ubuntu)
