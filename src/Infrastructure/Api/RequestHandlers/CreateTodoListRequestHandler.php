<?php declare(strict_types=1);

namespace App\Infrastructure\Api\RequestHandlers;

use App\Application\UseCases\TodoListCreate\TodoListCreateCommand;
use App\Application\Validator\ValidationException;
use App\Infrastructure\Api\ApiRequest;
use App\Infrastructure\Api\ApiRequestHandler;
use App\Infrastructure\Api\ApiResponse;
use App\Infrastructure\Api\Exceptions\BadRequestException;

class CreateTodoListRequestHandler extends ApiRequestHandler
{
    public function handle(ApiRequest $request): ApiResponse
    {
        $arguments = $request->getArguments();

        $command = new TodoListCreateCommand($arguments['name'] ?? '', $arguments['participants'] ?? []);

        try {
            $todoList = $this->commandBus->handle($command);
        } catch (ValidationException $x) {
            throw new BadRequestException($x->getValidationErrors(), $x);
        }

        return new ApiResponse(
            ApiResponse::STATUS_CODE_CREATED,
            $this->serializer->serialize($todoList)
        );
    }
}
