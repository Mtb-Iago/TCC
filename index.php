<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Factory\AppFactory;
use Slim\Handlers\Strategies\RequestHandler;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;
use Psr\Http\Message\ServerRequestInterface;

header('Access-Control-Allow-Origin:*'); 
header('Access-Control-Allow-Headers:X-Request-With');

header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

require_once 'loads.php';
$app = AppFactory::create();

$app->add(function (Request $request, RequestHandlerInterface $handler): Response {
    $routeContext = RouteContext::fromRequest($request);
    $routingResults = $routeContext->getRoutingResults();
    $methods = $routingResults->getAllowedMethods();
    $requestHeaders = $request->getHeaderLine('Access-Control-Request-Headers');

    $response = $handler->handle($request);

    $response = $response->withHeader('Access-Control-Allow-Origin', '*');
    $response = $response->withHeader('Access-Control-Allow-Methods', implode(',', $methods));
    $response = $response->withHeader('Access-Control-Allow-Headers', $requestHeaders);

    return $response;
});

// The RoutingMiddleware should be added after our CORS middleware so routing is performed first
$app->addRoutingMiddleware();
$app->addBodyParsingMiddleware();

$errorMiddleware = $app->addErrorMiddleware(true, true, true);
// Get the default error handler and register my custom error renderer.
$errorHandler = $errorMiddleware->getDefaultErrorHandler()->forceContentType('application/json');



$app->group('/api/user/', function (RouteCollectorProxy $group) {
    $group->post('login/', UserController::class . ':login');
    $group->post('insert-user/', UserController::class . ':insert_user');
    $group->post('logout/', UserController::class . ':logout')->add(AuthMiddleware::class);
    $group->post('list-user/', UserController::class . ':list_user')->add(AuthMiddleware::class);
});


$app->group('/api/posts/', function (RouteCollectorProxy $group) {
    $group->post('list-posts/', PostsController::class . ':list_posts');
    $group->post('insert-posts/', PostsController::class . ':insert_posts');
    $group->post('update-status-post/', PostsController::class . ':update_status_post');
})->add(AuthMiddleware::class);

$app->group('/api/category/', function (RouteCollectorProxy $group) {
    $group->post('list-category/', CategoryController::class . ':list_category');
    $group->post('insert-category/', CategoryController::class . ':insert_category');
})->add(AuthMiddleware::class);

try {
    $app->run();
} catch (\Throwable $th) {
    print_r($th);
    exit(json_encode(["resposta" => "Erro, página não encontrada.."]));
}