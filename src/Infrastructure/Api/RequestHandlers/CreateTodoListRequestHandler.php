<?php declare(strict_types=1);

namespace App\Infrastructure\Api\RequestHandlers;

use App\Application\UseCases\TodoListCreateCommand;
use App\Infrastructure\Api\ApiRequest;
use App\Infrastructure\Api\ApiRequestHandler;
use App\Infrastructure\Api\ApiResponse;

class CreateTodoListRequestHandler extends ApiRequestHandler
{
    public function handle(ApiRequest $request): ApiResponse
    {
        $arguments = $request->getArguments();

        $command = new TodoListCreateCommand($arguments['name'] ?? '', $arguments['participants'] ?? []);

        $todoList = $this->commandBus->handle($command);

        return new ApiResponse(
            ApiResponse::STATUS_CODE_CREATED,
            $this->serializer->serialize($todoList)
        );
    }
}
