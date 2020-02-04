<?php declare(strict_types=1);

namespace App\Infrastructure\Api\RequestHandlers;

use App\Application\UseCases\TodoListItemDelete\TodoListItemDeleteCommand;
use App\Infrastructure\Framework\Api\ApiRequest;
use App\Infrastructure\Framework\Api\ApiRequestHandler;
use App\Infrastructure\Framework\Api\ApiResponse;

class DeleteTodoListItemRequestHandler extends ApiRequestHandler
{
    /**
     * @param ApiRequest $request
     *
     * @return ApiResponse
     */
    function handle(ApiRequest $request): ApiResponse
    {
        $arguments = $request->getArguments();
        $command = new TodoListItemDeleteCommand($arguments['list-id'], $arguments['item-id']);
        $apiResponse = new ApiResponse(ApiResponse::STATUS_CODE_OK, $this->handleCommand($command));

        return $apiResponse;
    }
}
