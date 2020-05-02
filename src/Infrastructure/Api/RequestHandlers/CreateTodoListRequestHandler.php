<?php declare(strict_types=1);

namespace App\Infrastructure\Api\RequestHandlers;

use App\Application\UseCases\TodoList\CreateListCommand;
use App\Infrastructure\Framework\Api\ApiRequest;
use App\Infrastructure\Framework\Api\ApiRequestHandler;
use App\Infrastructure\Framework\Api\ApiResponse;

class CreateTodoListRequestHandler extends ApiRequestHandler
{
    public function handle(ApiRequest $request): ApiResponse
    {
        $arguments = $request->getArguments();
        $command = new CreateListCommand($arguments['name'] ?? '', $arguments['participants'] ?? []);
        $apiResponse = new ApiResponse(ApiResponse::STATUS_CODE_CREATED, $this->handleCommand($command));

        return $apiResponse;
    }
}
