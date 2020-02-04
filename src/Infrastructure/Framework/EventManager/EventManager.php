<?php declare(strict_types=1);

namespace App\Infrastructure\Framework\EventManager;

use App\Domain\EventManager\DomainEventInterface;
use App\Domain\EventManager\EventHandlerInterface;
use App\Domain\EventManager\EventManagerInterface;

class EventManager implements EventManagerInterface
{
    private $eventHandlers = [];

    /**
     * @param DomainEventInterface $event
     */
    public function emitEvent(DomainEventInterface $event)
    {
        $name = $event->getName();
        if (!empty($this->eventHandlers[$name])) {
            array_walk(
                $this->eventHandlers[$name],
                function (EventHandlerInterface $eventHandler) use ($event) {
                    $eventHandler->handleEvent($event);
                }
            );
        }
    }

    /**
     * @param string $eventName
     * @param EventHandlerInterface $eventHandler
     */
    public function subscribe(string $eventName, EventHandlerInterface $eventHandler)
    {
        $this->eventHandlers[$eventName][] = $eventHandler;
    }
}
