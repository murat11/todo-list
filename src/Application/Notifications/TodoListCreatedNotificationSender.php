<?php declare(strict_types=1);

namespace App\Application\Notifications;

use App\Domain\EventManager\DomainEventInterface;
use App\Domain\EventManager\EventHandlerInterface;
use App\Domain\Events\TodoListCreatedEvent;

class TodoListCreatedNotificationSender implements EventHandlerInterface
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
        if (!($domainEvent instanceof TodoListCreatedEvent)) {
            return;
        }

        $todoList = $domainEvent->getTodoList();
        $notification = new Notification(
            $todoList->getParticipantEmails(),
            TodoListCreatedEvent::NAME,
            [
                'todo_list' => $todoList,
            ]
        );
        $this->notifier->send($notification);
    }
}
