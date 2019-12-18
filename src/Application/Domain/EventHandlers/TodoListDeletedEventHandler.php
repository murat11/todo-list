<?php declare(strict_types=1);

namespace App\Application\Domain\EventHandlers;

use App\Application\Notifications\Notification;
use App\Application\Notifications\NotificationSenderInterface;
use App\Domain\EventManager\DomainEventInterface;
use App\Domain\EventManager\EventHandlerInterface;
use App\Domain\TodoList\Events\TodoListDeletedEvent;

class TodoListDeletedEventHandler implements EventHandlerInterface
{
    /**
     * @var NotificationSenderInterface
     */
    private $notificationSender;

    /**
     * @param NotificationSenderInterface $notificationSender
     */
    public function __construct(NotificationSenderInterface $notificationSender)
    {
        $this->notificationSender = $notificationSender;
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
        $this->notificationSender->send($notification);
    }
}
