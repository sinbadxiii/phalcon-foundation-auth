<?php

declare(strict_types=1);

namespace Sinbadxiii\PhalconFoundationAuth;

use Phalcon\Mvc\Router\Group;

/**
 * Class AuthRoutes
 * @package Sinbadxiii\PhalconAuth
 */
class Routes
{
    public static function routes($options = [])
    {
        $router = new Group();
        $router->addGet("/login", 'App\Controllers\Auth\Login::loginForm')->setName("login-form");
        $router->addPost("/login", 'App\Controllers\Auth\Login::login')->setName("login");
        $router->addGet("/logout", 'App\Controllers\Auth\Login::logout')->setName("logout");

        $router->addGet("/register", 'App\Controllers\Auth\Register::registerForm')->setName("register-form");
        $router->addPost("/register", 'App\Controllers\Auth\Register::register')->setName("register");
        return $router;
    }

    public static function jwt($options = [])
    {
        $router = new Group();
        $router->addPost("/auth/login", 'App\Controllers\Auth\Login::login')->setName("login");
        $router->addPost("/auth/logout", 'App\Controllers\Auth\Login::logout')->setName("logout");
        $router->addPost("/auth/refresh", 'App\Controllers\Auth\Login::refresh')->setName("refresh");
        $router->addPost("/auth/me", 'App\Controllers\Auth\Login::me')->setName("me");

        return $router;
    }
}