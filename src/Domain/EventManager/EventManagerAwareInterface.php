<?php declare(strict_types=1);

namespace App\Domain\EventManager;

interface EventManagerAwareInterface
{
    /**
     * @param EventManagerInterface $eventManager
     */
    public function setEventManager(EventManagerInterface $eventManager);
}
