<?php declare(strict_types=1);

namespace App\Infrastructure\Api\RequestHandlers;

use App\Application\UseCases\TodoListDeleteCompletedItems\TodoListDeleteCompletedItemsCommand;
use App\Infrastructure\Framework\Api\Exceptions\BadRequestException;
use App\Infrastructure\Framework\Api\ApiRequest;
use App\Infrastructure\Framework\Api\ApiRequestHandler;
use App\Infrastructure\Framework\Api\ApiResponse;

class BatchDeleteTodoListItemsRequestHandler extends ApiRequestHandler
{
    public function handle(ApiRequest $request): ApiResponse
    {
        $arguments = $request->getArguments();
        if (!array_key_exists('completed', $arguments)) {
            throw new BadRequestException(['Only completed items can be batch-deleted']);
        }
        $command = new TodoListDeleteCompletedItemsCommand($arguments['list-id'] ?? '');
        $apiResponse = new ApiResponse(ApiResponse::STATUS_CODE_OK, $this->handleCommand($command));

        return $apiResponse;
    }
}
