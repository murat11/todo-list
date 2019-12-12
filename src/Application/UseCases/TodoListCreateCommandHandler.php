<?php declare(strict_types=1);

namespace App\Application\UseCases;

use App\Application\Repository\TodoListRepositoryAwareInterface;
use App\Application\Repository\TodoListRepositoryAwareTrait;

/**
 * Class TodoListCreateCommandHandler
 */
class TodoListCreateCommandHandler implements TodoListRepositoryAwareInterface
{
    use TodoListRepositoryAwareTrait;

    /**
     * @param TodoListCreateCommand $command
     *
     * @return string ID of TodoList
     */
    public function handle(TodoListCreateCommand $command): string
    {

        $todoList = $command->buildTodoListInstance();
        $this->todoListRepository->addNew($todoList);

        return $todoList->getId();
    }
}
