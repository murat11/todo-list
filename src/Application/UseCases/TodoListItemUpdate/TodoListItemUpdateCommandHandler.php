<?php declare(strict_types=1);

namespace App\Application\UseCases\TodoListItemUpdate;

use App\Domain\TodoList\TodoListItem;
use App\Domain\TodoList\TodoListManager\TodoListManagerAwareInterface;
use App\Domain\TodoList\TodoListManager\TodoListManagerAwareTrait;

class TodoListItemUpdateCommandHandler  implements TodoListManagerAwareInterface
{
    use TodoListManagerAwareTrait;

    /**
     * @param TodoListItemUpdateCommand $command
     *
     * @return TodoListItem
     */
    public function handle(TodoListItemUpdateCommand $command): TodoListItem
    {
        $todoList = $this->todoListManager->findTodoListById($command->getListId());
        $todoListItem = $todoList->getItemById($command->getListItemId());
        $todoListItem->setTitle($command->getTitle());
        $todoListItem->setCompleted($command->isCompleted());

        $this->todoListManager->updateTodoList($todoList);

        return $todoListItem;
    }
}
