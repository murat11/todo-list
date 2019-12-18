<?php declare(strict_types=1);

namespace App\Domain\TodoList\TodoListManager;

interface TodoListManagerAwareInterface
{
    /**
     * @param TodoListManager $todoListManager
     */
    public function setTodoListManager(TodoListManager $todoListManager);
}
