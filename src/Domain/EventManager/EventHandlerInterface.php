<?php declare(strict_types=1);

namespace App\Domain\EventManager;

interface EventHandlerInterface
{
    /**
     * @param DomainEventInterface $domainEvent
     */
    public function handleEvent(DomainEventInterface $domainEvent);
}
