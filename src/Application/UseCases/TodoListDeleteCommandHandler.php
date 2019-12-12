<?php declare(strict_types=1);

namespace App\Application\UseCases;

use App\Application\TodoListRepositoryInterface;

class TodoListDeleteCommandHandler
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
     * @param TodoListDeleteCommand $command
     */
    public function handle(TodoListDeleteCommand $command): void
    {
        $this->todoListRepository->deleteById($command->getListId());
    }
}
