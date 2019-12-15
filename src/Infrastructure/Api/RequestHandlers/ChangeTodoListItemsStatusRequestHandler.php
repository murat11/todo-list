<?php declare(strict_types=1);

namespace App\Infrastructure\Api\RequestHandlers;

use App\Application\UseCases\TodoListChangeItemsStatus\TodoListChangeItemsStatusCommand;
use App\Infrastructure\Api\ApiRequest;
use App\Infrastructure\Api\ApiRequestHandler;
use App\Infrastructure\Api\ApiResponse;

class ChangeTodoListItemsStatusRequestHandler extends ApiRequestHandler
{
    public function handle(ApiRequest $request): ApiResponse
    {
        $arguments = $request->getArguments();
        $completed = $arguments['completed'] ?? null;
        if (!is_null($completed)) {
            $completed = (bool) $completed;
        }

        $command = new TodoListChangeItemsStatusCommand($arguments['list-id'] ?? '', $completed);
        $apiResponse = new ApiResponse(ApiResponse::STATUS_CODE_OK, $this->handleCommand($command));

        return $apiResponse;
    }
}
