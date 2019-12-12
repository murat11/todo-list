<?php declare(strict_types=1);

namespace App\Application\UseCases;

use App\Application\TodoListRepositoryInterface;

class TodoListReadItemsCommandHandler
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
     * @param TodoListReadItemsCommand $command
     *
     * @return array
     */
    public function handle(TodoListReadItemsCommand $command): array
    {
        $todoList = $this->todoListRepository->findOneById($command->getListId());

        return $todoList->getItems();
    }
}
