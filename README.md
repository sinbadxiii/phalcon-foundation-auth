# Phalcon Foundation Auth

Базоый набор для работы аутентификации [sinbadxiii/phalcon-auth](https://github.com/sinbadxiii/phalcon-auth) из коробки.

В данной библиотеки присутствуют роуты, контроллеры, мидлвары для организации регистрации и авторизации пользователей.

<p align="center">
<a href="LICENSE"><img src="https://img.shields.io/badge/license-MIT-brightgreen?style=flat-square" alt="Software License"></img></a>
<a href="https://packagist.org/packages/sinbadxiii/phalcon-foundation-auth"><img src="https://img.shields.io/packagist/dt/sinbadxiii/phalcon-foundation-auth?style=flat-square" alt="Packagist Downloads"></img></a>
<a href="https://github.com/sinbadxiii/phalcon-foundation-auth/releases"><img src="https://img.shields.io/github/release/sinbadxiii/phalcon-foundation-auth?style=flat-square" alt="Latest Version"></img></a>
</p>

## Installation

Phalcon 4 or Phalcon 5

PHP 7.2-8.0.

Require the project using composer:

`composer require "sinbadxiii/phalcon-foundation-auth:^v1.0.0"`

Run helper script 

`vendor/bin/phauth -c`

Который скопирует контроллеры и middleware в ваше приложение, для более гибкого использования.

Если ваше приложение имеет нестандартные пути, то следует указать путь к папке с прилоджением (по умолчанию "app"), например укажем что у нас вместо папки app используется папка src.

`vendor/bin/phauth -c -b src`

Так же, если изменилась папка с контроллерами (по умолчанию "controllers" в app), то укажем новую папку, например Controllers:

`vendor/bin/phauth -c -b src -s Controllers`

После копирования в папке контроллеров появится папка Auth с контроллерами LoginController, RegisterController и пр.

А в корне app появится папка Security, в которой будет лежать мидлвар Authenticate, для проверки авторизации пользователя.

В сервисах нужно будет зарегистрировать провайдер Auth

```php 
$di->setShared("auth", function () {
    return new Sinbadxiii\PhalconAuth\Auth();
});
```

или

```php 
$authProvider = new \Sinbadxiii\PhalconAuth\AuthProvider();
$authProvider->register($di);
```

а в диспетчере приаттачить мидлвар

```php 
$di->setShared('dispatcher', function () use ($di) {

    $dispatcher = new Dispatcher();
    $eventsManager = $di->getShared('eventsManager');
    $eventsManager->attach('dispatch', new App\Security\Authenticate());
    $dispatcher->setEventsManager($eventsManager);

    return $dispatcher;
});
```
В файл вашего роутер добавить

```php 
<?php

use Sinbadxiii\PhalconFoundationAuth\Routes as AuthRoutes;


$router = $di->getRouter();

...

$router->mount(AuthRoutes::routes());

...

$router->handle($_SERVER['REQUEST_URI']);
```

Так же нужно будет проследить, чтобы уже имелись следующие сервис провайдеры:

- session
- cache
- security
- cookies

```php 
$di->setShared('session', function () {
    $session = new SessionManager();
    $files = new SessionAdapter([
        'savePath' => sys_get_temp_dir(),
    ]);
    $session->setAdapter($files);
    $session->start();

    return $session;
});

$di->setShared("cache", function () {

    $configCache = $this->getConfig()->path("cache");

    $serializerFactory = new Phalcon\Storage\SerializerFactory();
    $adapterFactory    = new Phalcon\Cache\AdapterFactory($serializerFactory);

    $adapter           = $adapterFactory->newInstance(
        $configCache->default, $configCache->options->toArray(),
    );

    return new Phalcon\Cache($adapter);
});

$di->set("security", function ()  use ($di) {
        $security = new Phalcon\Security();
        $security->setDI($di);
        return $security;
    }
);

//лучше всего шифровать данные кук
$di->set('cookies', function (){
        $cookies = new Phalcon\Http\Response\Cookie();
        $cookies->useEncryption(true);
        $salt = $this->getConfig()->path('app.key');
        $cookies->setSignKey($salt);
        return $cookies;
    }
);
```

либо воспользоваться сервисами из библиотеки:

```php 
$securityProvider = new \Sinbadxiii\PhalconFoundationAuth\Providers\SecurityProvider();
$securityProvider->register($di);

$cookieProvider = new \Sinbadxiii\PhalconFoundationAuth\Providers\CookiesProvider();
$cookieProvider->register($di);
```

а в config иметь доступ к настройкам cache, например Phalcon Redis Adapter

```php 
'cache' => [
        'default' => 'redis',
        'options' => [
            'options' => [
                'defaultSerializer' => 'Json',
                'scheme' => 'tcp',
                'host'   => '127.0.0.1',
                'port'   => 6379,
                'lifetime' => 3600,
            ],
        ]
    ]
```

### License
The MIT License (MIT). Please see [License File](https://github.com/sinbadxiii/phalcon-foundation-auth/blob/master/LICENSE) for more information.