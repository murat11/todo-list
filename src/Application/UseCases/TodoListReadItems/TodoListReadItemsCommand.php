<?php declare(strict_types=1);

namespace App\Application\UseCases\TodoListReadItems;

/**
 * Class TodoListReadItemsCommand
 */
class TodoListReadItemsCommand
{
    /**
     * @var string
     */
    private $listId;

    /**
     * @param string $listId
     */
    public function __construct(string $listId)
    {
        $this->listId = $listId;
    }

    /**
     * @return string
     */
    public function getListId(): string
    {
        return $this->listId;
    }
}
