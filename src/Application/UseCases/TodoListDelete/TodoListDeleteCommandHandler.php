<?php declare(strict_types=1);

namespace App\Application\UseCases\TodoListDelete;

use App\Application\Repository\TodoListRepositoryAwareInterface;
use App\Application\Repository\TodoListRepositoryAwareTrait;
use App\Domain\EventManager\EventManagerAwareTrait;
use App\Domain\Events\TodoListDeletedEvent;

class TodoListDeleteCommandHandler implements TodoListRepositoryAwareInterface
{
    use TodoListRepositoryAwareTrait;
    use EventManagerAwareTrait;

    /**
     * @param TodoListDeleteCommand $command
     */
    public function handle(TodoListDeleteCommand $command): void
    {
        $listId = $command->getListId();
        $todoList = $this->todoListRepository->findOneById($listId);
        $this->todoListRepository->deleteById($listId);
        $this->eventManager->emitEvent(new TodoListDeletedEvent($todoList));
    }
}
