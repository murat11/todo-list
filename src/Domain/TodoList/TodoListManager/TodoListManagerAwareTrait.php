<?php declare(strict_types=1);

namespace App\Domain\TodoList\TodoListManager;

trait TodoListManagerAwareTrait
{
    /**
     * @var TodoListManager
     */
    private $todoListManager;

    /**
     * @param TodoListManager $todoListManager
     */
    public function setTodoListManager(TodoListManager $todoListManager): void
    {
        $this->todoListManager = $todoListManager;
    }
}
