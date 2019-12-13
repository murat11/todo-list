<?php declare(strict_types=1);

namespace App\Domain;

/**
 * Class TodoListItem
 */
class TodoListItem
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var bool
     */
    private $completed;

    /**
     * @return string
     */
    public function getId(): ?string
    {
        return $this->id;
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
     * @param string $title
     *
     * @return TodoListItem
     */
    public function setTitle(string $title): TodoListItem
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @param bool $completed
     *
     * @return TodoListItem
     */
    public function setCompleted(bool $completed): TodoListItem
    {
        $this->completed = $completed;

        return $this;
    }
}
