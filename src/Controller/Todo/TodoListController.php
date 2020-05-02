<?php declare(strict_types=1);

namespace App\Controller\Todo;

use App\Application\UseCases\TodoList\CreateListCommand;
use App\Domain\TodoList\TodoList;
use App\Domain\TodoList\TodoListManager\TodoListManager;

class TodoListController
{
    /**
     * @param CreateListCommand $command
     * @param TodoListManager $todoListManager
     *
     * @return TodoList
     */
    public function createList(CreateListCommand $command, TodoListManager $todoListManager): TodoList
    {
        $todoList = $command->buildTodoListInstance();
        $todoListManager->addNewTodoList($todoList);

        return $todoList;
    }
}
