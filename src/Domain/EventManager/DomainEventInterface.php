<?php declare(strict_types=1);

namespace App\Domain\EventManager;

interface DomainEventInterface
{
    /**
     * @return string
     */
    public function getName(): string;
}
