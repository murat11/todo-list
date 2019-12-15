<?php declare(strict_types=1);

namespace App\Infrastructure\CommandBus;

use App\Application\Repository\TodoListRepositoryAwareInterface;
use App\Application\Repository\TodoListRepositoryInterface;
use App\Application\EventManager\EventManagerAwareInterface;
use App\Domain\EventManager\EventManagerInterface;

class HandlerResolver
{
    /**
     * @var TodoListRepositoryInterface
     */
    private $todoListRepository;

    /**
     * @var EventManagerInterface
     */
    private $eventManager;

    /**
     * @param TodoListRepositoryInterface $todoListRepository
     * @param EventManagerInterface $eventManager
     */
    public function __construct(TodoListRepositoryInterface $todoListRepository, EventManagerInterface $eventManager)
    {
        $this->todoListRepository = $todoListRepository;
        $this->eventManager = $eventManager;
    }

    /**
     * @param $command
     *
     * @return mixed
     */
    public function getHandlerForCommand($command)
    {
        $classOfTheCommand = get_class($command);
        $classOfTheCommandHandler = $classOfTheCommand.'Handler';

        $handler = new $classOfTheCommandHandler();
        if ($handler instanceof TodoListRepositoryAwareInterface) {
            $handler->setTodoListRepository($this->todoListRepository);
        }
        if ($handler instanceof EventManagerAwareInterface) {
            $handler->setEventManager($this->eventManager);
        }

        return $handler;
    }
}
