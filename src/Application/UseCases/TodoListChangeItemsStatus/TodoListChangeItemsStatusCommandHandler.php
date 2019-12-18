<?php declare(strict_types=1);

namespace App\Application\UseCases\TodoListChangeItemsStatus;

use App\Domain\TodoList\TodoListManager\TodoListManagerAwareInterface;
use App\Domain\TodoList\TodoListManager\TodoListManagerAwareTrait;

/**
 * Class TodoListChangeItemsStatusCommandHandler
 */
class TodoListChangeItemsStatusCommandHandler implements TodoListManagerAwareInterface
{
    use TodoListManagerAwareTrait;

    /**
     * @param TodoListChangeItemsStatusCommand $command
     */
    public function handle(TodoListChangeItemsStatusCommand $command): void
    {
        $todoList = $this->todoListManager->findTodoListById($command->getListId());
        $todoList->applyNewStatusToAllItems($command->isCompleted());
        $this->todoListManager->updateTodoList($todoList);
    }
}
