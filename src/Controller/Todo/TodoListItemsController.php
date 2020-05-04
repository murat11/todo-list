<?php declare(strict_types=1);

namespace App\Controller\Todo;

use App\Application\UseCases\TodoList\AddListItemCommand;
use App\Application\UseCases\TodoList\GetListItemsQuery;
use App\Application\UseCases\TodoList\UpdateListItemCommand;
use App\Domain\TodoList\TodoListItem;
use App\Domain\TodoList\TodoListManager\TodoListManager;

class TodoListItemsController
{
    /**
     * @param GetListItemsQuery $query
     * @param TodoListManager $todoListManager
     *
     * @return TodoListItem[]
     */
    public function getListItems(GetListItemsQuery $query, TodoListManager $todoListManager): array
    {
        return $todoListManager->findTodoListById($query->getListId())->getItems();
    }

    /**
     * @param AddListItemCommand $command
     * @param TodoListManager $todoListManager
     *
     * @return TodoListItem
     */
    public function addListItem(AddListItemCommand $command, TodoListManager $todoListManager): TodoListItem
    {
        $todoList = $todoListManager->findTodoListById($command->getListId());
        $todoListItem = $command->buildTodoListItem();
        $todoList->addItem($todoListItem);
        $todoListManager->updateTodoList($todoList);

        return $todoListItem;
    }

    /**
     * @param UpdateListItemCommand $command
     * @param TodoListManager $todoListManager
     *
     * @return TodoListItem
     */
    public function updateListItem(UpdateListItemCommand $command, TodoListManager $todoListManager): TodoListItem
    {
        $todoList = $todoListManager->findTodoListById($command->getListId());
        $todoListItem = $todoList->getItemById($command->getListItemId());
        $todoListItem->setTitle($command->getTitle());
        $todoListItem->setCompleted($command->isCompleted());

        $todoListManager->updateTodoList($todoList);

        return $todoListItem;
    }
}
