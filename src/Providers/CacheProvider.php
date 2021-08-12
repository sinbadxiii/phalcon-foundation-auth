<?php

declare(strict_types=1);

namespace Sinbadxiii\PhalconFoundationAuth\Providers;

use Phalcon\Config;
use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;
use Phalcon\Cache;
use Phalcon\Cache\AdapterFactory;
use Phalcon\Storage\SerializerFactory;

class CacheProvider implements ServiceProviderInterface
{
    /**
     * @var string
     */
    protected $providerName = 'cache';

    /**
     * @param DiInterface $di
     */
    public function register(DiInterface $di): void
    {
        $configCache = $di->getShared("config")->path("cache");

        if (!$configCache) {
            $configCache = new Config(
                [
                    'default' => "redis",
                    'redis' => [
                        'options' => [
                            'defaultSerializer' => 'Json',
                            'scheme' => 'tcp',
                            'host'   => '127.0.0.1',
                            'port'   => 6379,
                            'lifetime' => 3600
                        ],
                    ],
                ]
            );
        }

        $di->setShared($this->providerName, function () use ($configCache) {
            $serializerFactory = new SerializerFactory();
            $adapterFactory    = new AdapterFactory($serializerFactory);
            $options = $configCache->{$configCache->default}->options;
            $adapter           = $adapterFactory->newInstance(
                $configCache->default, $options->toArray(),
            );

            return new Cache($adapter);
        });
    }
}