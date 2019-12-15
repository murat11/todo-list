<?php declare(strict_types=1);

namespace App\Application\UseCases\TodoListDelete;

use App\Application\EventManager\EventManagerAwareInterface;
use App\Application\Repository\TodoListRepositoryAwareInterface;
use App\Application\Repository\TodoListRepositoryAwareTrait;
use App\Application\EventManager\EventManagerAwareTrait;
use App\Domain\Events\TodoListDeletedEvent;

class TodoListDeleteCommandHandler implements TodoListRepositoryAwareInterface, EventManagerAwareInterface
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
        if (isset($this->eventManager)) {
            $this->eventManager->emitEvent(new TodoListDeletedEvent($todoList));
        }
    }
}
