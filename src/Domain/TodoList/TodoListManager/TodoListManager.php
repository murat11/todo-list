<?php declare(strict_types=1);

namespace App\Domain\TodoList\TodoListManager;

use App\Domain\RepositoryInterface;
use App\Domain\TodoList\Events\TodoListCreatedEvent;
use App\Domain\TodoList\Events\TodoListDeletedEvent;
use App\Domain\TodoList\Exception\TodoListNotFoundException;
use App\Domain\TodoList\TodoList;
use App\Infrastructure\EventManager\EventManager;

class TodoListManager
{
    /**
     * @var RepositoryInterface
     */
    private $repository;

    /**
     * @var EventManager
     */
    private $eventManager;

    /**
     * TodoListManager constructor.
     * @param RepositoryInterface $repository
     * @param EventManager $eventManager
     */
    public function __construct(RepositoryInterface $repository, EventManager $eventManager)
    {
        $this->repository = $repository;
        $this->eventManager = $eventManager;
    }

    /**
     * @param TodoList $todoList
     */
    public function addNewTodoList(TodoList $todoList)
    {
        $this->repository->addNew($todoList);
        $this->eventManager->emitEvent(new TodoListCreatedEvent($todoList));
    }

    /**
     * @param TodoList $todoList
     */
    public function updateTodoList(TodoList $todoList)
    {
        $this->repository->save($todoList);
    }

    /**
     * @param TodoList $todoList
     */
    public function deleteTodoList(TodoList $todoList)
    {
        $this->repository->deleteById($todoList->getId());
        $this->eventManager->emitEvent(new TodoListDeletedEvent($todoList));
    }

    /**
     * @param string $listId
     *
     * @return TodoList
     */
    public function findTodoListById(string $listId): TodoList
    {
        $todoList = $this->repository->findOneById($listId);
        if (empty($todoList)) {
            throw new TodoListNotFoundException($listId);
        }

        return $todoList;
    }
}
