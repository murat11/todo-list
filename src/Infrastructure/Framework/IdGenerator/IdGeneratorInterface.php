<?php declare(strict_types=1);

namespace App\Infrastructure\Framework\IdGenerator;

interface IdGeneratorInterface
{
    /**
     * @return string
     */
    public function generateId(): string;
}
