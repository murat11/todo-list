<?php declare(strict_types=1);

namespace App\Application\UseCases;

/**
 * Class TodoListItemUpdateCommand
 */
class TodoListItemUpdateCommand
{
    /**
     * @var string
     */
    private $listId;

    /**
     * @var string
     */
    private $listItemId;

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
     * @param string $listItemId
     * @param string $title
     * @param bool $completed
     */
    public function __construct(string $listId, string $listItemId, string $title, bool $completed)
    {
        $this->listId = $listId;
        $this->listItemId = $listItemId;
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
    public function getListItemId(): string
    {
        return $this->listItemId;
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
}
