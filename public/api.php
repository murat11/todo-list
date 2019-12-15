<?php

use App\Infrastructure\Api\ApiRequest;
use App\Infrastructure\Api\FrontController;
use App\Infrastructure\Api\RequestHandlers\CreateTodoListItemRequestHandler;
use App\Infrastructure\Api\RequestHandlers\CreateTodoListRequestHandler;
use App\Infrastructure\Api\RequestHandlers\DeleteTodoListItemRequestHandler;
use App\Infrastructure\Api\RequestHandlers\BatchDeleteTodoListItemsRequestHandler;
use App\Infrastructure\Api\RequestHandlers\DeleteTodoListRequestHandler;
use App\Infrastructure\Api\RequestHandlers\GetTodoListItemsRequestHandler;
use App\Infrastructure\Api\RequestHandlers\UpdateTodoListItemRequestHandler;

require '../vendor/autoload.php';
require '../src/assemble.php';

$routing = [
    ['POST', 'api.php/lists', CreateTodoListRequestHandler::class],
    ['GET', 'api.php/lists/:list-id/todos', GetTodoListItemsRequestHandler::class],
    ['POST', 'api.php/lists/:list-id/todos', CreateTodoListItemRequestHandler::class],
    ['PUT', 'api.php/lists/:list-id/todos/:item-id', UpdateTodoListItemRequestHandler::class],
    ['DELETE', 'api.php/lists/:list-id/todos/:item-id', DeleteTodoListItemRequestHandler::class],
    ['DELETE', 'api.php/lists/:list-id', DeleteTodoListRequestHandler::class],
    ['DELETE', 'api.php/lists/:list-id/todos/', BatchDeleteTodoListItemsRequestHandler::class],
];

$apiController = FrontController::createInstance($routing, $requestHandlerFactory);
$apiController->handle(ApiRequest::fromGlobals())->render();
