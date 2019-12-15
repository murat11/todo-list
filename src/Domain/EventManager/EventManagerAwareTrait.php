<?php declare(strict_types=1);

namespace App\Domain\EventManager;

trait EventManagerAwareTrait
{

    /**
     * @var EventManagerInterface
     */
    protected $eventManager;

    /**
     * @param EventManagerInterface $eventManager
     */
    public function setEventManager(EventManagerInterface $eventManager)
    {
        $this->eventManager = $eventManager;
    }
}
