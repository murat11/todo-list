<?php declare(strict_types=1);

namespace App\Application\UseCases\TodoListCreate;

use App\Domain\TodoList\TodoList;
use App\Domain\TodoList\TodoListManager\TodoListManagerAwareInterface;
use App\Domain\TodoList\TodoListManager\TodoListManagerAwareTrait;

/**
 * Class TodoListCreateCommandHandler
 */
class TodoListCreateCommandHandler implements TodoListManagerAwareInterface
{
    use TodoListManagerAwareTrait;

    /**
     * @param TodoListCreateCommand $command
     *
     * @return TodoList
     */
    public function handle(TodoListCreateCommand $command): TodoList
    {
        $todoList = $command->buildTodoListInstance();
        $this->todoListManager->addNewTodoList($todoList);

        return $todoList;
    }
}
