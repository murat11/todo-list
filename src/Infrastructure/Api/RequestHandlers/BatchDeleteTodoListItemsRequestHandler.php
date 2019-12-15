<?php declare(strict_types=1);

namespace App\Infrastructure\Api\RequestHandlers;

use App\Application\UseCases\TodoListDeleteCompletedItems\TodoListDeleteCompletedItemsCommand;
use App\Infrastructure\Api\Exceptions\BadRequestException;
use App\Infrastructure\Api\Exceptions\NotFoundException;
use App\Infrastructure\Repository\Exception\NotFoundException as NotFoundRepositoryException;
use App\Infrastructure\Api\ApiRequest;
use App\Infrastructure\Api\ApiRequestHandler;
use App\Infrastructure\Api\ApiResponse;

class BatchDeleteTodoListItemsRequestHandler extends ApiRequestHandler
{
    public function handle(ApiRequest $request): ApiResponse
    {
        $arguments = $request->getArguments();
        if (!array_key_exists('completed', $arguments)) {
            throw new BadRequestException(['Only completed items can be batch-deleted']);
        }
        $command = new TodoListDeleteCompletedItemsCommand($arguments['list-id'] ?? '');

        try {
            $apiResponse = new ApiResponse(
                ApiResponse::STATUS_CODE_OK,
                $this->handleCommand($command)
            );
        } catch (NotFoundRepositoryException $x) {
            throw new NotFoundException(sprintf('Todo List with ID %s not found', $command->getListId()));
        }

        return $apiResponse;
    }
}
