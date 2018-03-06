<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';

$app = new \Slim\App([
    "settings"  => [
        "determineRouteBeforeAppMiddleware" => true,
    ]
]);

//ERROR HANDLERS
$container = $app->getContainer();

$container['notFoundHandler'] = function() {
	return function($request,$response) {
		return $response->withJson([
			"error" => [
				"message" => "Route not found"
			]
		],404);
	};
};

//CORS
$app->add(function($request, $response, $next) {
    $route = $request->getAttribute("route");

    $methods = [];

    if (!empty($route)) {
        $pattern = $route->getPattern();

        foreach ($this->router->getRoutes() as $route) {
            if ($pattern === $route->getPattern()) {
                $methods = array_merge_recursive($methods, $route->getMethods());
            }
        }
    } else {
        $methods[] = $request->getMethod();
    }

    $response = $next($request, $response);


    return $response->withHeader("Access-Control-Allow-Methods", implode(",", $methods));
});

//ROUTES
require_once '../api/routes/genre.php';
require_once '../api/routes/titles.php';
require_once '../api/routes/year.php';

$app->run();








