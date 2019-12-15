<?php declare(strict_types=1);

namespace App\Domain\Events;

use App\Domain\EventManager\DomainEventInterface;
use App\Domain\TodoList;

class TodoListCreatedEvent implements DomainEventInterface
{
    const NAME = 'todo_list_created';

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
