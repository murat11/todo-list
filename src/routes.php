<?php declare(strict_types=1);

use App\Infrastructure\Api\RequestHandlers\BatchDeleteTodoListItemsRequestHandler;
use App\Infrastructure\Api\RequestHandlers\ChangeTodoListItemsStatusRequestHandler;
use App\Infrastructure\Api\RequestHandlers\CreateTodoListItemRequestHandler;
use App\Infrastructure\Api\RequestHandlers\CreateTodoListRequestHandler;
use App\Infrastructure\Api\RequestHandlers\DeleteTodoListItemRequestHandler;
use App\Infrastructure\Api\RequestHandlers\DeleteTodoListRequestHandler;
use App\Infrastructure\Api\RequestHandlers\GetTodoListItemsRequestHandler;
use App\Infrastructure\Api\RequestHandlers\UpdateTodoListItemRequestHandler;

return [
    ['POST', 'api.php/lists', CreateTodoListRequestHandler::class],
    ['GET', 'api.php/lists/:list-id/todos', GetTodoListItemsRequestHandler::class],
    ['POST', 'api.php/lists/:list-id/todos', CreateTodoListItemRequestHandler::class],
    ['PUT', 'api.php/lists/:list-id/todos/:item-id', UpdateTodoListItemRequestHandler::class],
    ['DELETE', 'api.php/lists/:list-id/todos/:item-id', DeleteTodoListItemRequestHandler::class],
    ['DELETE', 'api.php/lists/:list-id', DeleteTodoListRequestHandler::class],
    ['DELETE', 'api.php/lists/:list-id/todos/', BatchDeleteTodoListItemsRequestHandler::class],
    ['PATCH', 'api.php/lists/:list-id/todos', ChangeTodoListItemsStatusRequestHandler::class],
];
