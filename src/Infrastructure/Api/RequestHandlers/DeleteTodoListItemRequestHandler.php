<?php declare(strict_types=1);

namespace App\Infrastructure\Api\RequestHandlers;

use App\Application\UseCases\TodoListItemDelete\TodoListItemDeleteCommand;
use App\Domain\Exception\NotFoundException as TodoListItemNotFoundException;
use App\Infrastructure\Api\ApiRequest;
use App\Infrastructure\Api\ApiRequestHandler;
use App\Infrastructure\Api\ApiResponse;
use App\Infrastructure\Api\Exceptions\NotFoundException;
use App\Infrastructure\Repository\Exception\NotFoundException as NotFoundRepositoryException;

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

        try {
            $apiResponse = new ApiResponse(
                ApiResponse::STATUS_CODE_OK,
                $this->handleCommand($command)
            );
        } catch (NotFoundRepositoryException $x) {
            throw new NotFoundException(sprintf('Todo List with ID %s not found', $command->getListId()));
        } catch (TodoListItemNotFoundException $x) {
            throw new NotFoundException(sprintf('Todo List Item with ID %s not found', $command->getListItemId()));
        }

        return $apiResponse;
    }
}
