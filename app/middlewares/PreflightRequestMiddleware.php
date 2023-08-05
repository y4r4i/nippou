<?php

declare(strict_types=1);

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Exception\HttpBadRequestException;

class PreflightRequestMiddleware
{


    public function __invoke(Request $request, RequestHandler $handler): ResponseInterface
    {
        $headers = getallheaders();
        if ($request->getMethod() != 'GET') {
            error_log($headers['x-requested-with']);
            if (array_key_exists('x-requested-with', $headers) and $headers['x-requested-with'] == 'XMLHttpRequest') {
                return $handler->handle($request);
            }
        } else {
            return $handler->handle($request);
        }

        throw new HttpBadRequestException($request);

    }//end __invoke()


}//end class
