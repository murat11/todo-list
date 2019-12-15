<?php

use App\Infrastructure\Api\ApiRequest;
use App\Infrastructure\Api\FrontController;
use App\Infrastructure\Api\RequestHandlers\CreateTodoListItemRequestHandler;
use App\Infrastructure\Api\RequestHandlers\CreateTodoListRequestHandler;
use App\Infrastructure\Api\RequestHandlers\DeleteTodoListRequestHandler;
use App\Infrastructure\Api\RequestHandlers\GetTodoListItemsRequestHandler;

require '../vendor/autoload.php';
require '../src/assemble.php';

$routing = [
    ['POST', 'api.php/lists', CreateTodoListRequestHandler::class],
    ['GET', 'api.php/lists/:list-id/todos', GetTodoListItemsRequestHandler::class],
    ['POST', 'api.php/lists/:list-id/todos', CreateTodoListItemRequestHandler::class],
    ['DELETE', 'api.php/lists/:list-id', DeleteTodoListRequestHandler::class],
];

$apiController = FrontController::createInstance($routing, $requestHandlerFactory);
$apiController->handle(ApiRequest::fromGlobals())->render();
