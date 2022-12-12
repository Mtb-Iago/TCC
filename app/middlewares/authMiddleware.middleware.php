<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Server\RequestHandlerInterface;

class AuthMiddleware
{
    /**
     * Example middleware invokable class
     *
     * @param  RequestHandler $handler PSR-15 request handler
     * @return Response
     */
    public function __invoke(Request $request, RequestHandlerInterface $handler) : Response 
    {
        try {
            $token = str_replace("Bearer ", "", $_SERVER["REDIRECT_HTTP_AUTHORIZATION"]);
            $token = (array)JWT::decode($token ? $token : "", new Key($_ENV['JWT_KEY'], 'HS256'));
        } catch (\Throwable $th) {
            switch ($th->getMessage()) {
                case 'Malformed UTF-8 characters':
                    
                    header( 'HTTP/1.0 401 Não Autorizado, favor realizar novo login' );
                    die(json_encode(["status" => false, "http-code" => 401, "message" => "Token Inválido..", "data" => []]));
                case 'Expired token':
                    
                    header( 'HTTP/1.0 401 Não Autorizado, favor realizar novo login' );
                    die(json_encode(["status" => false, "http-code" => 401, "message" => "Token expirado..", "data" => []]));
                case 'Signature verification failed':
                    
                    header( 'HTTP/1.0 401 Não Autorizado, favor realizar novo login' );
                    die(json_encode(["status" => false, "http-code" => 401, "message" => "Token Inválido..", "data" => []]));
                case 'Wrong number of segments':
                    
                    header( 'HTTP/1.0 401 Não Autorizado, favor realizar novo login' );
                    die(json_encode(["status" => false, "http-code" => 401, "message" => "Não existe Token..", "data" => []]));
                default:
                    throw new Exception($th->getMessage(), $th->getCode());
            }  
        }
        $response = $handler->handle($request);
        return $response;
    }
}