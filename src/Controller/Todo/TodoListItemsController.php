<?php declare(strict_types=1);

namespace App\Controller\Todo;

use App\Application\UseCases\TodoList\GetListItemsQuery;
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
}
