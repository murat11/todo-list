<?php declare(strict_types=1);

namespace App\Application\UseCases\TodoListCreate;

use App\Application\Repository\TodoListRepositoryAwareInterface;
use App\Application\Repository\TodoListRepositoryAwareTrait;
use App\Domain\TodoList;

/**
 * Class TodoListCreateCommandHandler
 */
class TodoListCreateCommandHandler implements TodoListRepositoryAwareInterface
{
    use TodoListRepositoryAwareTrait;

    /**
     * @param TodoListCreateCommand $command
     *
     * @return TodoList
     */
    public function handle(TodoListCreateCommand $command): TodoList
    {

        $todoList = $command->buildTodoListInstance();
        $this->todoListRepository->addNew($todoList);

        return $todoList;
    }
}
