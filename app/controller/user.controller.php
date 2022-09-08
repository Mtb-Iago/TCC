<?php
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class UserController
{
    public function __construct() 
    {
        $this->user = new UserModel();
    }

    public function list_user(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {    
        try {
            $request->getParsedBody() ? $params = $request->getParsedBody() : $params = null;
            $result = $this->user->list_user($params);
            $response->getBody()->write(json_encode($result));

            return $response->withStatus(200)->withHeader('Content-type', 'application/json');
        } catch (\Throwable $th) {
            $response->getBody()->write(throw $th);
            return $response->withStatus(400)->withHeader('Content-type', 'application/json');
        }
    }

    public function insert_user(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {    
        try {
            $request->getParsedBody() ? $params = $request->getParsedBody() : $params = null;
            
            $result = $this->user->insert_user($params);
            $response->getBody()->write(json_encode($result));

            return $response->withStatus(200)->withHeader('Content-type', 'application/json');
        } catch (\Throwable $th) {
            $response->getBody()->write(throw $th);
            return $response->withStatus(400)->withHeader('Content-type', 'application/json');
        }
    }
}