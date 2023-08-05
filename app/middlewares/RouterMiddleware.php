<?php

declare(strict_types=1);

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

require_once __DIR__.'/../components/Config.php';
require_once __DIR__.'/../attributes/RouteGroup.php';
require_once __DIR__.'/../attributes/Route.php';

class RouterMiddleware
{


    public function __construct(private App $app, private Config $config)
    {

    }//end __construct()


    public function __invoke(Request $request, RequestHandler $handler): ResponseInterface
    {
        $paths = glob(__DIR__.'/../../routes/*.php');
        if (is_array($paths) === true) {
            foreach ($paths as $path) {
                $this->path2route($path);
            }
        }

        $routeCollector = $this->app->getRouteCollector();
        if ($this->config->__invoke()['cache']) {
            $routeCollector->setCacheFile(__DIR__.'/../../var/cache/route.cache');
        }

        return $handler->handle($request);

    }//end __invoke()


    private function path2route(string $path): void
    {
        include_once $path;
        $class           = pathinfo($path, PATHINFO_FILENAME);
        $reflectionClass = new ReflectionClass($class);
        foreach ($reflectionClass->getAttributes() as $classAttribute) {
            if ($classAttribute->getName() === 'RouteGroup') {
                $groupAttribute = $classAttribute->newInstance();
                $this->app->group(
                    $groupAttribute->pattern,
                    function (RouteCollectorProxy $group) use ($class, $reflectionClass) {
                        foreach ($reflectionClass->getMethods() as $method) {
                            if (!in_array($method->getName(), ['__construct', '__destruct'])) {
                                foreach ($method->getAttributes() as $attribute) {
                                    if ($attribute->getName() == 'Route') {
                                        $methodAttribute = $attribute->newInstance();
                                        $pattern         = $methodAttribute->pattern;
                                        $pattern         = rtrim($pattern, '/');
                                        $group->map($methodAttribute->methods, $pattern, [$class, $method->getName()]);
                                    }
                                }
                            }//end if
                        }//end foreach
                    }
                );
            }
        }//end foreach

    }//end path2route()


}//end class
