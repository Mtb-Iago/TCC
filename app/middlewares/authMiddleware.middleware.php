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
            $token = (array)JWT::decode(@$_SESSION['token'] ? $_SESSION['token'] : "", new Key($_ENV['JWT_KEY'], 'HS256'));
        } catch (\Throwable $th) {
            switch ($th->getMessage()) {
                case 'Malformed UTF-8 characters':
                    session_unset();
                    session_destroy();
                    http_response_code(400);
                    exit(json_encode(["status" => false, "http-code" => 400, "message" => "Token Inválido..", "data" => []]));
                    break;
                case 'Expired token':
                    session_unset();
                    session_destroy();
                    http_response_code(400);
                    exit(json_encode(["status" => false, "http-code" => 400, "message" => "Token expirado..", "data" => []]));
                    break;
                case 'Signature verification failed':
                    session_unset();
                    session_destroy();
                    http_response_code(400);
                    exit(json_encode(["status" => false, "http-code" => 400, "message" => "Token Inválido..", "data" => []]));
                    break;
                case 'Wrong number of segments':
                    session_unset();
                    session_destroy();
                    http_response_code(400);
                    exit(json_encode(["status" => false, "http-code" => 400, "message" => "Não existe Token..", "data" => []]));
                    break;
                default:
                    throw new Exception($th->getMessage(), $th->getCode());
                    break;
            }  
        }
        $response = $handler->handle($request);
        $response->getBody()->write(json_encode(["payload" => $token]));
        return $response;
    }

    public static function teste(Request $request, RequestHandlerInterface $handler, $app)  {
        $response = $handler->handle($request);
        $dateOrTime = (string) $response->getBody();
    
        $response = $app->getResponseFactory()->createResponse();
        $response->getBody()->write('It is now ' . $dateOrTime . '. Enjoy!');
    
        return $response;

    }
}