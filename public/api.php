<?php

use App\Infrastructure\Api\ApiRequest;
use App\Infrastructure\Api\FrontController;
use App\Infrastructure\Api\RequestHandlers\CreateTodoListRequestHandler;
use App\Infrastructure\Api\RequestHandlers\DeleteTodoListRequestHandler;

require '../vendor/autoload.php';
require '../src/assemble.php';

$routing = [
    ['POST', 'api.php/lists', CreateTodoListRequestHandler::class],
    ['DELETE', 'api.php/lists/:list-id', DeleteTodoListRequestHandler::class]
];

$apiController = FrontController::createInstance($routing, $requestHandlerFactory);
$apiController->handle(ApiRequest::fromGlobals())->render();
