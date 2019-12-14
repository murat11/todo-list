<?php declare(strict_types=1);

namespace App\Infrastructure\CommandBus;

interface CommandBusAwareInterface
{
    /**
     * @param CommandBus $commandBus
     *
     */
    public function setCommandBus(CommandBus $commandBus);
}
