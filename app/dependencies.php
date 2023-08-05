<?php

declare(strict_types=1);

use DI\Bridge\Slim\Bridge;
use DI\ContainerBuilder;
use Medoo\Medoo;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Slim\App;

require __DIR__.'/../app/components/Config.php';
require __DIR__.'/../app/components/BasePath.php';
require __DIR__.'/../app/components/UniqueId.php';

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions(
        [
            App::class      => function (ContainerInterface $container) {
                return Bridge::create($container);
            },
            Config::class   => function () {
                return new Config();
            },
            BasePath::class => function () {
                return new BasePath();
            },
            UniqueId::class => function () {
                return new UniqueId();
            },
            Logger::class   => function () {
                $logger        = new Logger('app');
                $streamHandler = new StreamHandler(__DIR__.'/../var/log/error.log', 100);
                $logger->pushHandler($streamHandler);
                return $logger;
            },
            Medoo::class    => function (Config $config) {
                return new Medoo($config()['database']);
            },
        ]
    );

};
