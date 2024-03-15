<?php

use Tuupola\Middleware\CorsMiddleware;
use Slim\Factory\AppFactory;

header('Content-Type: application/json; charset=utf-8');

require '../vendor/autoload.php';
require_once '../src/secrets.php';

$app = AppFactory::create();

$app->add(new CorsMiddleware([
    "origin" => ["*"],
    "methods" => ["GET", "POST", "PUT", "PATCH", "DELETE"],
    "headers.allow" => ["Accept", "Content-Type", "Authorization"],
    "headers.expose" => [],
    "credentials" => true,
    "cache" => 0,
]));

$app->addErrorMiddleware(true, true, true);

require '../src/routes.php';

$app->run();