<?php

use Medoo\Medoo;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpUnauthorizedException;

#[RouteGroup('/users')]
class Users
{


    #[Route('/me', ['get'])]
    public function me(Request $request, Response $response, Medoo $db): Response
    {
        if (is_null($_SESSION['user_id'])) {
            throw new HttpUnauthorizedException($request);
        }

        $user = $db->select(
            'user',
            [
                'role_id',
                'is_active',
                'email',
                'family_name',
                'given_name',
                'start_time',
                'closing_time',
                'break_time',
            ],
            [
                'id' => $_SESSION['user_id'],
            ]
        )[0];
        if (!$user['is_active']) {
            throw new HttpUnauthorizedException($request);
        }

        unset($user['is_active']);
        $body = $response->getBody();
        $body->write(json_encode($user));
        return $response->withHeader('Content-Type', 'application/json');

    }//end me()


}//end class
