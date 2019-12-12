<?php declare(strict_types=1);

namespace App\Application\UseCases;

use App\Application\TodoListRepositoryInterface;

/**
 * Class TodoListCreateCommandHandler
 */
class TodoListCreateCommandHandler
{
    /**
     * @var TodoListRepositoryInterface
     */
    private $todoListRepository;

    /**
     * @param TodoListRepositoryInterface $todoListRepository
     */
    public function __construct(TodoListRepositoryInterface $todoListRepository)
    {
        $this->todoListRepository = $todoListRepository;
    }

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
