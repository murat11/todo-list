<?php declare(strict_types=1);

namespace App\Application\UseCases\TodoListCreateItem;

use App\Application\Repository\TodoListRepositoryAwareInterface;
use App\Application\Repository\TodoListRepositoryAwareTrait;
use App\Domain\TodoListItem;

class TodoListCreateItemCommandHandler implements TodoListRepositoryAwareInterface
{
    use TodoListRepositoryAwareTrait;

    /**
     * @param TodoListCreateItemCommand $command
     *
     * @return TodoListItem
     */
    public function handle(TodoListCreateItemCommand $command): TodoListItem
    {
        $todoList = $this->todoListRepository->findOneById($command->getListId());
        $todoListItem = $command->buildTodoListItem();

        $todoList->addItem($todoListItem);
        $this->todoListRepository->save($todoList);

        return $todoListItem;
    }
}
