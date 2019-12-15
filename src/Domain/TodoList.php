<?php declare(strict_types=1);

namespace App\Domain;

use App\Domain\Exception\TodoListItemNotFoundException;

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
    public function getId(): ?string
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
        foreach ($this->items as &$listItem) {
            if ($listItem->getId() === $itemId) {
                return $listItem;
            }
        }

        throw new TodoListItemNotFoundException($itemId);
    }

    /**
     * @param bool $completed
     *
     * @return TodoList
     */
    public function applyNewStatusToAllItems(bool $completed): self
    {
        array_walk(
            $this->items,
            function (TodoListItem $listItem) use ($completed) {
                $listItem->setCompleted($completed);
            }
        );

        return $this;
    }

    /**
     * @param string $itemId
     *
     * @return TodoList
     */
    public function deleteItemById(string $itemId): self
    {
        foreach ($this->items as $key => $listItem) {
            if ($listItem->getId() === $itemId) {
                unset($this->items[$key]);

                return $this;
            }
        }

        throw new TodoListItemNotFoundException($itemId);
    }

    public function deleteCompletedItems(): self
    {
        foreach ($this->items as $key => $listItem) {
            if ($listItem->isCompleted()) {
                unset($this->items[$key]);
            }
        }

        return $this;
    }

    /**
     * @return string[]
     */
    public function getParticipantEmails(): array
    {
        return $this->participantEmails;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
