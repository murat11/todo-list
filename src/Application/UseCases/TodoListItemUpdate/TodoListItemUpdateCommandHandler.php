<?php declare(strict_types=1);

namespace App\Application\UseCases\TodoListItemUpdate;

use App\Application\Repository\TodoListRepositoryAwareInterface;
use App\Application\Repository\TodoListRepositoryAwareTrait;
use App\Domain\TodoListItem;

class TodoListItemUpdateCommandHandler implements TodoListRepositoryAwareInterface
{
    use TodoListRepositoryAwareTrait;

    /**
     * @param TodoListItemUpdateCommand $command
     *
     * @return TodoListItem
     */
    public function handle(TodoListItemUpdateCommand $command): TodoListItem
    {
        $todoList = $this->todoListRepository->findOneById($command->getListId());
        $todoListItem = $todoList->getItemById($command->getListItemId());
        $todoListItem->setTitle($command->getTitle());
        $todoListItem->setCompleted($command->isCompleted());

        $this->todoListRepository->save($todoList);

        return $todoListItem;
    }
}
