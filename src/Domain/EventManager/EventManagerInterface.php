<?php declare(strict_types=1);

namespace App\Domain\EventManager;

interface EventManagerInterface
{
    /**
     * @param DomainEventInterface $event
     *
     * @return EventManagerInterface
     */
    public function emitEvent(DomainEventInterface $event): self;

    /**
     * @param string $eventName
     * @param EventHandlerInterface $eventHandler
     *
     * @return EventManagerInterface
     */
    public function subscribe(string $eventName, EventHandlerInterface $eventHandler): self;
}
