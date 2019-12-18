<?php declare(strict_types=1);

namespace App\Application\UseCases\TodoListDelete;

use App\Domain\TodoList\TodoListManager\TodoListManagerAwareInterface;
use App\Domain\TodoList\TodoListManager\TodoListManagerAwareTrait;

class TodoListDeleteCommandHandler implements TodoListManagerAwareInterface
{
    use TodoListManagerAwareTrait;

    /**
     * @param TodoListDeleteCommand $command
     */
    public function handle(TodoListDeleteCommand $command): void
    {
        $todoList = $this->todoListManager->findTodoListById($command->getListId());
        $this->todoListManager->deleteTodoList($todoList);
    }
}
