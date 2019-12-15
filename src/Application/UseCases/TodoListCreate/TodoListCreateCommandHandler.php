<?php declare(strict_types=1);

namespace App\Application\UseCases\TodoListCreate;

use App\Application\Repository\TodoListRepositoryAwareInterface;
use App\Application\Repository\TodoListRepositoryAwareTrait;
use App\Application\EventManager\EventManagerAwareInterface;
use App\Application\EventManager\EventManagerAwareTrait;
use App\Domain\Events\TodoListCreatedEvent;
use App\Domain\TodoList;

/**
 * Class TodoListCreateCommandHandler
 */
class TodoListCreateCommandHandler implements TodoListRepositoryAwareInterface, EventManagerAwareInterface
{
    use TodoListRepositoryAwareTrait;
    use EventManagerAwareTrait;

    /**
     * @param TodoListCreateCommand $command
     *
     * @return TodoList
     */
    public function handle(TodoListCreateCommand $command): TodoList
    {

        $todoList = $command->buildTodoListInstance();
        $this->todoListRepository->addNew($todoList);
        if (isset($this->eventManager)) {
            $this->eventManager->emitEvent(new TodoListCreatedEvent($todoList));
        }

        return $todoList;
    }
}
