<?php

declare(strict_types=1);

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Exception\HttpUnauthorizedException;

class XSRFMiddleware
{


    public function __invoke(Request $request, RequestHandler $handler): ResponseInterface
    {
        $headers = getallheaders();
        if ($request->getMethod() != 'GET') {
            if (array_key_exists('x-xsrf-token', $headers) and array_key_exists('XSRF-TOKEN', $_SESSION)) {
                if ($headers['x-xsrf-token'] == $_SESSION['XSRF-TOKEN']) {
                    return $handler->handle($request);
                }
            }

            throw new HttpUnauthorizedException($request);
        }

        return $handler->handle($request);

    }//end __invoke()


}//end class
