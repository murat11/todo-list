<?php

use App\Infrastructure\Api\ApiRequest;
use App\Infrastructure\Api\FrontController;
use App\Infrastructure\Api\RequestHandlers\CreateTodoListRequestHandler;

require '../vendor/autoload.php';
require '../src/assemble.php';

$routing = [
    ['POST', 'api.php/lists', CreateTodoListRequestHandler::class]
];

$apiController = FrontController::createInstance($routing, $requestHandlerFactory);
$apiController->handle(ApiRequest::fromGlobals())->render();
