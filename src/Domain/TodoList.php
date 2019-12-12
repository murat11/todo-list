<?php declare(strict_types=1);

namespace App\Domain;

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
    public function setName(string $name): TodoList
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
}
