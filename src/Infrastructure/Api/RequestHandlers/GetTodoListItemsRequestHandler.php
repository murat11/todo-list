<?php declare(strict_types=1);

namespace App\Infrastructure\Api\RequestHandlers;

use App\Application\UseCases\TodoList\GetListItemsQuery;
use App\Infrastructure\Framework\Api\ApiRequest;
use App\Infrastructure\Framework\Api\ApiRequestHandler;
use App\Infrastructure\Framework\Api\ApiResponse;
use App\Infrastructure\Framework\Api\Exceptions\NotFoundException;
use App\Domain\TodoList\Exception\TodoListNotFoundException as NotFoundRepositoryException;

class GetTodoListItemsRequestHandler extends ApiRequestHandler
{
    /**
     * @param ApiRequest $request
     *
     * @return ApiResponse
     */
    function handle(ApiRequest $request): ApiResponse
    {
        $arguments = $request->getArguments();
        $command = new GetListItemsQuery($arguments['list-id']);

        try {
            $result = $this->commandBus->handle($command);
        } catch (NotFoundRepositoryException $x) {
            throw new NotFoundException(sprintf('Todo List with ID %s not found', $command->getListId()));
        }

        $result = array_map([$this->serializer, 'serialize'], $result);
        $apiResponse = new ApiResponse(ApiResponse::STATUS_CODE_OK, $result);

        return $apiResponse;
    }
}
