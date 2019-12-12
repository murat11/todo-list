<?php declare(strict_types=1);

namespace App\Domain\Exception;

use InvalidArgumentException;
use Throwable;

class NotFoundException extends InvalidArgumentException
{
    public function __construct(string $type, string $id, Throwable $previous = null)
    {
        parent::__construct(sprintf('%s with ID %s not found', $type, $id), 404, $previous);
    }
}
