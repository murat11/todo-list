<?php declare(strict_types=1);

namespace App\Application\UseCases\TodoListCreateItem;

use App\Domain\TodoList\TodoListItem;
use App\Domain\TodoList\TodoListManager\TodoListManagerAwareInterface;
use App\Domain\TodoList\TodoListManager\TodoListManagerAwareTrait;

class TodoListCreateItemCommandHandler implements TodoListManagerAwareInterface
{
    use TodoListManagerAwareTrait;

    /**
     * @param TodoListCreateItemCommand $command
     *
     * @return TodoListItem
     */
    public function handle(TodoListCreateItemCommand $command): TodoListItem
    {
        $todoList = $this->todoListManager->findTodoListById($command->getListId());
        $todoListItem = $command->buildTodoListItem();
        $todoList->addItem($todoListItem);
        $this->todoListManager->updateTodoList($todoList);

        return $todoListItem;
    }
}
