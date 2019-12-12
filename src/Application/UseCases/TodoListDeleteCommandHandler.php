<?php declare(strict_types=1);

namespace App\Application\UseCases;

use App\Application\Repository\TodoListRepositoryAwareInterface;
use App\Application\Repository\TodoListRepositoryAwareTrait;

class TodoListDeleteCommandHandler implements TodoListRepositoryAwareInterface
{
    use TodoListRepositoryAwareTrait;

    /**
     * @param TodoListDeleteCommand $command
     */
    public function handle(TodoListDeleteCommand $command): void
    {
        $this->todoListRepository->deleteById($command->getListId());
    }
}
