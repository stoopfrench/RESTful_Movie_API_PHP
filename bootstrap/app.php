<?php

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

$container['notAllowedHandler'] = function ($container) {
    return function ($request, $response, $methods) use ($container) {
        $responseData = [
            "error" => [
                "error" => "Method not allowed",
                "message" => "Must be one of the following: " . implode(',',$methods)
            ]
        ];
        $response->getBody()->rewind();
        return $response
            ->withStatus(405)
            ->withHeader('Content-type', 'application/json')
            ->write(json_encode($responseData));
    };
};

$container['errorHandler'] = function ($container) {
    return function ($request, $response, $exception) use ($container) {
        $responseData = [
            "error" => [
                "message" => "Something went wrong",
                "error" => $exception
            ]
        ];        
        $response->getBody()->rewind();
        return $response->withStatus(500)
                        ->withHeader('Content-Type', 'application/json')
                        ->write(json_encode($responseData));
    };
};
 
$container['phpErrorHandler'] = function ($container) {
    return function ($request, $response, $error) use ($container) {
        $responseData = [
            "error" => [
                "message" => "Something went wrong",
                "error" => $error
            ]
        ];
        $response->getBody()->rewind();
        return $response->withStatus(500)
                        ->withHeader('Content-Type', 'application/json')
                        ->write(json_encode($responseData));
    };
};

//CORS
$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization');
});


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
require_once '../api/Routes/genre.php';
require_once '../api/Routes/titles.php';
require_once '../api/Routes/year.php';