<?php declare(strict_types=1);

namespace App\Infrastructure\CommandBus;

use App\Application\Repository\TodoListRepositoryAwareInterface;
use App\Application\Repository\TodoListRepositoryInterface;

class HandlerResolver
{
    /**
     * @var TodoListRepositoryInterface
     */
    private $todoListRepository;

    /**
     * CommandBus constructor.
     * @param TodoListRepositoryInterface $todoListRepository
     */
    public function __construct(TodoListRepositoryInterface $todoListRepository)
    {
        $this->todoListRepository = $todoListRepository;
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

        return $handler;
    }
}
