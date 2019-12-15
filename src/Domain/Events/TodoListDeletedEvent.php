<?php declare(strict_types=1);

namespace App\Domain\Events;

use App\Domain\EventManager\DomainEventInterface;
use App\Domain\TodoList;

class TodoListDeletedEvent implements DomainEventInterface
{
    const NAME = 'todo_list_deleted';

    /**
     * @var TodoList
     */
    private $todoList;

    /**
     * @param TodoList $todoList
     */
    public function __construct(TodoList $todoList)
    {
        $this->todoList = $todoList;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::NAME;
    }

    /**
     * @return TodoList
     */
    public function getTodoList(): TodoList
    {
        return $this->todoList;
    }
}
