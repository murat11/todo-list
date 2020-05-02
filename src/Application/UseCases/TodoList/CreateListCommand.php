<?php declare(strict_types=1);

namespace App\Application\UseCases\TodoList;

use App\Domain\TodoList\TodoList;

/**
 * Class TodoListCreateCommand
 */
class CreateListCommand
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $participantEmails;

    /**
     * @param string $name
     * @param array $participantEmails
     */
    public function __construct(string $name, array $participantEmails)
    {
        $this->name = $name;
        $this->participantEmails = $participantEmails;
    }

    /**
     * @return array
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

    /**
     * @return TodoList
     */
    public function buildTodoListInstance(): TodoList
    {
        $todoList = new TodoList();
        $todoList->setName($this->name);
        foreach ($this->participantEmails as $participantEmail) {
            $todoList->addParticipantEmail($participantEmail);
        }

        return $todoList;
    }
}
