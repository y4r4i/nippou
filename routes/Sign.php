<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Medoo\Medoo;
use Slim\Exception\HttpBadRequestException as HttpBadRequestException;
use Slim\Exception\HttpUnauthorizedException;
use Swaggest\JsonSchema\Schema;

#[RouteGroup('/sign')]
class Sign
{


    #[Route('/up', ['post'])]
    public function up(Request $request, Response $response, Medoo $db, UniqueId $uniqueId): Response
    {
        $path   = implode('/', [__DIR__, '..', 'schemas', 'signup.json']);
        $data   = $request->getParsedBody();
        $schema = Schema::import($path);
        $schema->in((object) $data);
        if ($data['password'] != $data['password_confirm']) {
            throw new HttpBadRequestException($request);
        }

        unset($data['password_confirm']);
        $count      = $db->count('user');
        $data['id'] = $uniqueId->__invoke();
        if ($count == 0) {
            $data['role_id'] = 'Admin';
        }

        $data['password'] = password_hash($data['password'], PASSWORD_ARGON2ID);
        $this->db->insert('user', $data);
        $_SESSION['user_id'] = $data['id'];
        return $response;

    }//end up()


    #[Route('/in', ['post'])]
    public function in(Request $request, Response $response, Medoo $db): Response
    {
        $path   = implode('/', [__DIR__, '..', 'schemas', 'signin.json']);
        $data   = $request->getParsedBody();
        $schema = Schema::import($path);
        $schema->in((object) $data);
        $user = $db->select(
            'user',
            [
                'id',
                'password',
            ],
            [
                'email' => $data['email'],
            ]
        );
        if (!is_null($user) and isset($user[0]['password'])) {
            if (password_verify($data['password'], $user[0]['password'])) {
                $_SESSION['user_id'] = $user[0]['id'];
                return $response;
            }
        }

        throw new HttpUnauthorizedException($request);

    }//end in()


    #[Route('/out', ['get'])]
    public function out(Request $request, Response $response): Response
    {
        unset($_SESSION['user_id']);
        return $response;

    }//end out()


}//end class
