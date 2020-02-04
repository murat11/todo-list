<?php declare(strict_types=1);

namespace App\Infrastructure\Framework\CommandBus;

use App\Domain\TodoList\TodoListManager\TodoListManager;
use App\Domain\TodoList\TodoListManager\TodoListManagerAwareInterface;

class HandlerResolver
{
    /**
     * @var TodoListManager
     */
    private $todoListManager;

    /**
     * @param TodoListManager $todoListManager
     */
    public function __construct(TodoListManager $todoListManager)
    {
        $this->todoListManager = $todoListManager;
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
        if ($handler instanceof TodoListManagerAwareInterface) {
            $handler->setTodoListManager($this->todoListManager);
        }

        return $handler;
    }
}
