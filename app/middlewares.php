<?php

declare(strict_types=1);

use Monolog\Logger;
use Slim\App;
use Slim\Middleware\ContentLengthMiddleware;
use Slim\Middleware\MethodOverrideMiddleware;
use Slim\Middleware\OutputBufferingMiddleware;
use Slim\Psr7\Factory\StreamFactory;

return function (App $app) {
    $paths = glob(__DIR__.'/middlewares/*.php');
    if (is_array($paths)) {
        foreach ($paths as $path) {
            include_once $path;
            $app->add(pathinfo($path, PATHINFO_FILENAME));
        }
    }

    $app->addBodyParsingMiddleware();
    $outputBufferingMiddleware = new OutputBufferingMiddleware(new StreamFactory(), OutputBufferingMiddleware::PREPEND);
    $app->add($outputBufferingMiddleware);
    $methodOverrideMiddleware = new MethodOverrideMiddleware();
    $app->add($methodOverrideMiddleware);
    $logger = $app->getContainer()->get(Logger::class);
    $app->addErrorMiddleware(true, true, true, $logger);
    $contentLengthMiddleware = new ContentLengthMiddleware();
    $app->add($contentLengthMiddleware);

};
