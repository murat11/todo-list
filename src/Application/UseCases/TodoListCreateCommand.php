<?php declare(strict_types=1);

namespace App\Application\UseCases;

/**
 * Class TodoListCreateCommand
 */
class TodoListCreateCommand
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
}
