<?php

declare(strict_types=1);

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class CORSMiddleware
{


    public function __invoke(Request $request, RequestHandler $handler): ResponseInterface
    {
        $response = $handler->handle($request);
        return $response->withHeader('Access-Control-Allow-Origin', 'http://localhost/app')->withHeader('Access-Control-Allow-Headers', '*')->withHeader('Access-Control-Allow-Methods', '*');

    }//end __invoke()


}//end class
