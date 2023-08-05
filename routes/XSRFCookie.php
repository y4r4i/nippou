<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require_once __DIR__.'/../app/components/UniqueId.php';
#[RouteGroup('/xsrf-cookie')]
class XSRFCookie
{


    #[Route('/', ['get'])]
    public function __invoke(Request $request, Response $response, UniqueId $uniqueId): Response
    {
        $token                  = $uniqueId->__invoke();
        $_SESSION['XSRF-TOKEN'] = $token;
        setcookie(
            'XSRF-TOKEN',
            $token,
            [
                'expires'  => 0,
                'path'     => '/',
                'samesite' => 'strict',
                'secure'   => true,
            // 'httponly' => true,
            ]
        );
        return $response;

    }//end __invoke()


}//end class
