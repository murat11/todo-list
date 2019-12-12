<?php declare(strict_types=1);

namespace App\Application\UseCases;

/**
 * Class TodoListItemDeleteCommand
 */
class TodoListItemDeleteCommand
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
     * @param string $listId
     * @param string $listItemId
     */
    public function __construct(string $listId, string $listItemId)
    {
        $this->listId = $listId;
        $this->listItemId = $listItemId;
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
}
