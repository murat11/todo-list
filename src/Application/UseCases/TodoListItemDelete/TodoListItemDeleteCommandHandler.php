<?php declare(strict_types=1);

namespace App\Application\UseCases\TodoListItemDelete;

use App\Domain\TodoList\TodoListManager\TodoListManagerAwareInterface;
use App\Domain\TodoList\TodoListManager\TodoListManagerAwareTrait;

/**
 * Class TodoListItemDeleteCommandHandler
 */
class TodoListItemDeleteCommandHandler implements TodoListManagerAwareInterface
{
    use TodoListManagerAwareTrait;

    /**
     * @param TodoListItemDeleteCommand $command
     */
    public function handle(TodoListItemDeleteCommand $command): void
    {
        $todoList = $this->todoListManager->findTodoListById($command->getListId());
        $todoList->deleteItemById($command->getListItemId());

        $this->todoListManager->updateTodoList($todoList);
    }
}
