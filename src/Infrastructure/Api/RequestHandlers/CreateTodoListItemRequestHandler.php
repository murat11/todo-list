<?php declare(strict_types=1);

namespace App\Infrastructure\Api\RequestHandlers;

use App\Application\UseCases\TodoListCreateItem\TodoListCreateItemCommand;
use App\Infrastructure\Api\ApiRequest;
use App\Infrastructure\Api\ApiRequestHandler;
use App\Infrastructure\Api\ApiResponse;

class CreateTodoListItemRequestHandler extends ApiRequestHandler
{
    /**
     * @param ApiRequest $request
     *
     * @return ApiResponse
     */
    function handle(ApiRequest $request): ApiResponse
    {
        $arguments = $request->getArguments();
        $command = new TodoListCreateItemCommand(
            $arguments['list-id'],
            $arguments['title'] ?? '',
            $arguments['completed'] ?? false
        );

        $apiResponse = new ApiResponse(ApiResponse::STATUS_CODE_CREATED, $this->handleCommand($command));

        return $apiResponse;
    }
}
