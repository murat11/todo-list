<?php declare(strict_types=1);

namespace App\Application\UseCases\TodoListReadItems;

use App\Application\Repository\TodoListRepositoryAwareInterface;
use App\Application\Repository\TodoListRepositoryAwareTrait;

class TodoListReadItemsCommandHandler implements TodoListRepositoryAwareInterface
{
    use TodoListRepositoryAwareTrait;

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
