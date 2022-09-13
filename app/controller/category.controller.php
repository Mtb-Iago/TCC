<?php
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class CategoryController
{
    public function __construct() 
    {
        $this->category = new CategoryModel();
    }

    public function list_category(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {    
        try {
            $request->getParsedBody() ? $params = $request->getParsedBody() : $params = null;
            $result = $this->category->list_category($params);
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
            
            $result = $this->category->insert_category($params);
            $response->getBody()->write(json_encode($result));

            return $response->withStatus(200)->withHeader('Content-type', 'application/json');
        } catch (\Throwable $th) {
            $response->getBody()->write(throw $th);
            return $response->withStatus(400)->withHeader('Content-type', 'application/json');
        }
    }
}