<?php

declare(strict_types=1);

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Message;
use Slim\Psr7\Response;

class TrailingSlashMiddleware
{

    private BasePath $basePath;


    public function __construct(BasePath $basePath)
    {
        $this->basePath = $basePath;

    }//end __construct()


    public function __invoke(Request $request, RequestHandler $handler): Response|Message|ResponseInterface
    {
        $uri  = $request->getUri();
        $path = $uri->getPath();
        if ($this->basePath->__invoke().'/' != $path) {
            if ($path != '/' && str_ends_with($path, '/')) {
                $path = rtrim($path, '/');
                $uri  = $uri->withPath($path);
                if ($request->getMethod() == 'GET') {
                    $response = new Response();
                    return $response->withHeader('Location', (string) $uri)->withStatus(301);
                } else {
                    $request = $request->withUri($uri);
                }
            }
        }

        return $handler->handle($request);

    }//end __invoke()


}//end class
