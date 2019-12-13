<?php declare(strict_types=1);

namespace App\Infrastructure\Repository;

interface IdGeneratorInterface
{
    /**
     * @return string
     */
    public function generateId(): string;
}
