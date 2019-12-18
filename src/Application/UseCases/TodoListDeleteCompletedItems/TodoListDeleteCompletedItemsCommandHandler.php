<?php declare(strict_types=1);

namespace App\Application\UseCases\TodoListDeleteCompletedItems;

use App\Domain\TodoList\TodoListManager\TodoListManagerAwareInterface;
use App\Domain\TodoList\TodoListManager\TodoListManagerAwareTrait;

class TodoListDeleteCompletedItemsCommandHandler  implements TodoListManagerAwareInterface
{
    use TodoListManagerAwareTrait;

    /**
     * @param TodoListDeleteCompletedItemsCommand $command
     */
    public function handle(TodoListDeleteCompletedItemsCommand $command): void
    {
        $todoList = $this->todoListManager->findTodoListById($command->getListId());
        $todoList->deleteCompletedItems();

        $this->todoListManager->updateTodoList($todoList);
    }
}
