<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';

$app = new \Slim\App;

require_once '../api/routes/genre.php';
require_once '../api/routes/titles.php';
require_once '../api/routes/year.php';

$app->run();