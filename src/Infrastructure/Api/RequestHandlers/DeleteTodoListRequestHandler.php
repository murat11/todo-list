<?php declare(strict_types=1);

namespace App\Infrastructure\Api\RequestHandlers;

use App\Application\UseCases\TodoListDelete\TodoListDeleteCommand;
use App\Infrastructure\Framework\Api\ApiRequest;
use App\Infrastructure\Framework\Api\ApiRequestHandler;
use App\Infrastructure\Framework\Api\ApiResponse;

class DeleteTodoListRequestHandler extends ApiRequestHandler
{
    public function handle(ApiRequest $request): ApiResponse
    {
        $arguments = $request->getArguments();
        $command = new TodoListDeleteCommand($arguments['list-id'] ?? '');
        $apiResponse = new ApiResponse(ApiResponse::STATUS_CODE_OK, $this->handleCommand($command));

        return $apiResponse;
    }
}
