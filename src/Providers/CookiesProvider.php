<?php
declare(strict_types=1);

namespace Sinbadxiii\PhalconFoundationAuth\Providers;

use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;
use Phalcon\Http\Response\Cookies;


class CookiesProvider implements ServiceProviderInterface
{
    /**
     * @var string
     */
    protected $providerName = 'cookies';

    /**
     * @param DiInterface $di
     */
    public function register(DiInterface $di): void
    {
        $salt = $di->getShared('config')->path('app.key') ?? "Fds4!f$%2dg!~Gsa3DGdw";

        $di->set($this->providerName,
            function () use ($salt) {
                $cookies = new Cookies();
                $cookies->useEncryption(true);
                $cookies->setSignKey($salt);
                return $cookies;
            }
        );
    }
}