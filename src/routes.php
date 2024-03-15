<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Views\PhpRenderer;
use Simoncdt\Api\Controller\UserCtrl;



$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});



$app->get('/', [UserCtrl::class, 'allUsers']);
$app->get('/users', [UserCtrl::class, 'allUsers']);
$app->get('/user/{id}', [UserCtrl::class, 'oneUser']);
$app->post('/insert-user', [UserCtrl::class, 'insertUser']);