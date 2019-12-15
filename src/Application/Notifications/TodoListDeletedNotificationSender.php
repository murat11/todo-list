<?php declare(strict_types=1);

namespace App\Application\Notifications;

use App\Domain\EventManager\DomainEventInterface;
use App\Domain\EventManager\EventHandlerInterface;
use App\Domain\Events\TodoListDeletedEvent;
use App\Domain\TodoList;

class TodoListDeletedNotificationSender implements EventHandlerInterface
{
    /**
     * @var NotificationSenderInterface
     */
    private $notifier;

    /**
     * @param NotificationSenderInterface $notifier
     */
    public function __construct(NotificationSenderInterface $notifier)
    {
        $this->notifier = $notifier;
    }

    /**
     * @param DomainEventInterface $domainEvent
     */
    public function handleEvent(DomainEventInterface $domainEvent)
    {
        if (!($domainEvent instanceof TodoListDeletedEvent)) {
            return;
        }

        $notification = $this->createNotificationFromEvent($domainEvent->getTodoList());
        if (!empty($notification)) {
            $this->notifier->send($notification);
        }
    }

    /**
     * @param TodoList $todoList
     *
     * @return Notification
     */
    private function createNotificationFromEvent(TodoList $todoList): ?Notification
    {
        $participantEmails = $todoList->getParticipantEmails();
        if (empty($participantEmails)) {
            return null;
        }

        $body = $todoList->getName() . ' ' . $todoList->getId() . PHP_EOL;
        $body .= json_encode($todoList->getItems());

        return new Notification($participantEmails, 'Todo List DELETED', $body);
    }
}
