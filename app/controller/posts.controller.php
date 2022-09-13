<?php
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class PostsController
{
    public function __construct() 
    {
        $this->posts = new PostsModel();
    }

    public function list_posts(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {    
        try {
            $request->getParsedBody() ? $params = $request->getParsedBody() : $params = null;
            $result = $this->posts->list_posts($params);
            $response->getBody()->write(json_encode($result));

            return $response->withStatus(200)->withHeader('Content-type', 'application/json');
        } catch (\Throwable $th) {
            $response->getBody()->write(throw $th);
            return $response->withStatus(400)->withHeader('Content-type', 'application/json');
        }
    }

    public function insert_posts(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {    
        try {
            $request->getParsedBody() ? $params = $request->getParsedBody() : $params = null;
            
            $result = $this->posts->insert_post($params);
            $response->getBody()->write(json_encode($result));

            return $response->withStatus(200)->withHeader('Content-type', 'application/json');
        } catch (\Throwable $th) {
            $response->getBody()->write(throw $th);
            return $response->withStatus(400)->withHeader('Content-type', 'application/json');
        }
    }
    public function update_status_post(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {    
        try {
            $params = $request->getParsedBody();
            if (!@$params['id_post'] || !isset($params['accept_post'])) {
                $response->getBody()->write(json_encode(["status" => false, "http-code" => 400, "message" => "Error: Por favor preencha todos os campos requiridos..."]));
                return $response->withStatus(400)->withHeader('Content-type', 'application/json');
            }

            $result = $this->posts->update_status_post($params);
            $response->getBody()->write(json_encode($result));

            return $response->withStatus(200)->withHeader('Content-type', 'application/json');
        } catch (\Throwable $th) {
            $response->getBody()->write(throw $th);
            return $response->withStatus(400)->withHeader('Content-type', 'application/json');
        }
    }
    public function insert_category(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {    
        try {
            $request->getParsedBody() ? $params = $request->getParsedBody() : $params = null;
            
            $result = $this->posts->insert_post($params);
            $response->getBody()->write(json_encode($result));

            return $response->withStatus(200)->withHeader('Content-type', 'application/json');
        } catch (\Throwable $th) {
            $response->getBody()->write(throw $th);
            return $response->withStatus(400)->withHeader('Content-type', 'application/json');
        }
    }
}