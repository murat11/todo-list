<?php declare(strict_types=1);

namespace App\Application\EventManager;

use App\Domain\EventManager\EventManagerInterface;

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
