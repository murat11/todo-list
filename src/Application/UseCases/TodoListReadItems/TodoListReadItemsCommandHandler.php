<?php declare(strict_types=1);

namespace App\Application\UseCases\TodoListReadItems;

use App\Domain\TodoList\TodoListItem;
use App\Domain\TodoList\TodoListManager\TodoListManager;
use App\Domain\TodoList\TodoListManager\TodoListManagerAwareInterface;
use App\Domain\TodoList\TodoListManager\TodoListManagerAwareTrait;

class TodoListReadItemsCommandHandler implements TodoListManagerAwareInterface
{
    use TodoListManagerAwareTrait;

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
     * @param TodoListReadItemsCommand $command
     *
     * @return TodoListItem[]
     */
    public function handle(TodoListReadItemsCommand $command): array
    {
        $todoList = $this->todoListManager->findTodoListById($command->getListId());

        return $todoList->getItems();
    }
}
