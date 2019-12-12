<?php declare(strict_types=1);

namespace App\Application\UseCases;

use App\Application\Repository\TodoListRepositoryAwareInterface;
use App\Application\Repository\TodoListRepositoryAwareTrait;

/**
 * Class TodoListChangeItemsStatusCommandHandler
 */
class TodoListChangeItemsStatusCommandHandler implements TodoListRepositoryAwareInterface
{
    use TodoListRepositoryAwareTrait;

    /**
     * @param TodoListChangeItemsStatusCommand $command
     */
    public function handle(TodoListChangeItemsStatusCommand $command): void
    {
        $todoList = $this->todoListRepository->findOneById($command->getListId());
        $todoList->applyNewStatusToAllItems($command->isCompleted());

        $this->todoListRepository->save($todoList);
    }
}
