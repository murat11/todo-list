<?php declare(strict_types=1);

namespace App\Application\UseCases\TodoListDeleteCompletedItems;

use App\Application\Repository\TodoListRepositoryAwareInterface;
use App\Application\Repository\TodoListRepositoryAwareTrait;

class TodoListDeleteCompletedItemsCommandHandler implements TodoListRepositoryAwareInterface
{
    use TodoListRepositoryAwareTrait;

    /**
     * @param TodoListDeleteCompletedItemsCommand $command
     */
    public function handle(TodoListDeleteCompletedItemsCommand $command): void
    {
        $todoList = $this->todoListRepository->findOneById($command->getListId());
        $todoList->deleteCompletedItems();

        $this->todoListRepository->save($todoList);
    }
}
