<?php declare(strict_types=1);

namespace App\Application\UseCases;

use App\Application\Repository\TodoListRepositoryAwareInterface;
use App\Application\Repository\TodoListRepositoryAwareTrait;

/**
 * Class TodoListItemDeleteCommandHandler
 */
class TodoListItemDeleteCommandHandler implements TodoListRepositoryAwareInterface
{
    use TodoListRepositoryAwareTrait;

    /**
     * @param TodoListItemDeleteCommand $command
     */
    public function handle(TodoListItemDeleteCommand $command): void
    {
        $todoList = $this->todoListRepository->findOneById($command->getListId());
        $todoList->deleteItemById($command->getListItemId());

        $this->todoListRepository->save($todoList);
    }
}
