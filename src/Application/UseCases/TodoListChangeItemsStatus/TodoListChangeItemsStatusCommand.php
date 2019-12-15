<?php declare(strict_types=1);

namespace App\Application\UseCases\TodoListChangeItemsStatus;

/**
 * Class TodoListChangeItemsStatusCommand
 */
class TodoListChangeItemsStatusCommand
{
    /**
     * @var string
     */
    private $listId;

    /**
     * @var bool
     */
    private $completed;

    /**
     * @param string $listId
     * @param bool $completed
     */
    public function __construct(string $listId, bool $completed)
    {
        $this->listId = $listId;
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
     * @return bool
     */
    public function isCompleted(): bool
    {
        return $this->completed;
    }
}
