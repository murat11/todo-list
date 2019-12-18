<?php declare(strict_types=1);

namespace App\Application\UseCases\TodoListCreateItem;

use App\Domain\TodoList\TodoListItem;

/**
 * Class TodoListCreateItemCommand
 */
class TodoListCreateItemCommand
{
    /**
     * @var string
     */
    private $listId;

    /**
     * @var string
     */
    private $title;

    /**
     * @var bool
     */
    private $completed;

    /**
     * @param string $listId
     * @param string $title
     * @param bool $completed
     */
    public function __construct(string $listId, string $title, bool $completed)
    {
        $this->listId = $listId;
        $this->title = $title;
        $this->completed = $completed;
    }

    /**
     * @return string
     */
    public function getListId(): string
    {
        return $this->listId;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return bool
     */
    public function isCompleted(): bool
    {
        return $this->completed;
    }

    /**
     * @return TodoListItem
     */
    public function buildTodoListItem(): TodoListItem
    {
        $todoListItem = new TodoListItem();
        $todoListItem->setTitle($this->getTitle());
        $todoListItem->setCompleted($this->isCompleted());

        return $todoListItem;
    }
}
