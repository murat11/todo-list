<?php declare(strict_types=1);

namespace App\Application\EventManager;

use App\Domain\EventManager\EventManagerInterface;

interface EventManagerAwareInterface
{
    /**
     * @param EventManagerInterface $eventManager
     */
    public function setEventManager(EventManagerInterface $eventManager);
}
