<?php declare(strict_types=1);

namespace App\Infrastructure\Api\RequestHandlers;

use App\Application\UseCases\TodoListDelete\TodoListDeleteCommand;
use App\Infrastructure\Api\Exceptions\NotFoundException;
use App\Infrastructure\Repository\Exception\NotFoundException as NotFoundRepositoryException;
use App\Infrastructure\Api\ApiRequest;
use App\Infrastructure\Api\ApiRequestHandler;
use App\Infrastructure\Api\ApiResponse;

class DeleteTodoListRequestHandler extends ApiRequestHandler
{
    public function handle(ApiRequest $request): ApiResponse
    {
        $arguments = $request->getArguments();
        $command = new TodoListDeleteCommand($arguments['list-id'] ?? '');

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
