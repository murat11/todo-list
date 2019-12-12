<?php declare(strict_types=1);

namespace App\Domain;

use App\Domain\Exception\NotFoundException;

/**
 * Class TodoList
 */
class TodoList
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string[]
     */
    private $participantEmails = [];

    /**
     * @var TodoListItem[]
     */
    private $items = [];

    /**
     * @param string $email
     *
     * @return TodoList
     */
    public function addParticipantEmail(string $email): self
    {
        $this->participantEmails[] = $email;

        return $this;
    }

    /**
     * @param string $name
     *
     * @return TodoList
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return TodoListItem[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @param TodoListItem $item
     *
     * @return TodoList
     */
    public function addItem(TodoListItem $item): self
    {
        $this->items[] = $item;

        return $this;
    }

    /**
     * @param string $itemId
     *
     * @return TodoListItem
     */
    public function getItemById(string $itemId): TodoListItem
    {
        foreach ($this->getItems() as &$listItem) {
            if ($listItem->getId()) {
                return $listItem;
            }
        }

        throw new NotFoundException('ToDo List Item', $itemId);
    }
}
