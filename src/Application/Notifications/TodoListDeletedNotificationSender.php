<?php declare(strict_types=1);

namespace App\Application\Notifications;

use App\Domain\EventManager\DomainEventInterface;
use App\Domain\EventManager\EventHandlerInterface;
use App\Domain\Events\TodoListDeletedEvent;

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

        $todoList = $domainEvent->getTodoList();
        $notification = new Notification(
            $todoList->getParticipantEmails(),
            TodoListDeletedEvent::NAME,
            [
                'todo_list' => $todoList,
            ]
        );
        $this->notifier->send($notification);
    }
}
