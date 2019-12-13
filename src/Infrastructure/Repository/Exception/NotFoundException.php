<?php declare(strict_types=1);

namespace App\Infrastructure\Repository\Exception;

use Doctrine\DBAL\Exception\InvalidArgumentException;
use Throwable;

class NotFoundException extends InvalidArgumentException
{
    /**
     * @param string $table
     * @param string $id
     * @param Throwable $previous
     */
    public function __construct(string $table, string $id, Throwable $previous = null)
    {
        parent::__construct(sprintf('Can not find ID "%s" in table "%s"', $id, $table), 404, $previous);
    }
}
