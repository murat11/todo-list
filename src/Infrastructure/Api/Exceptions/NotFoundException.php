<?php declare(strict_types=1);

namespace App\Infrastructure\Api\Exceptions;

use InvalidArgumentException;
use Throwable;

class NotFoundException extends InvalidArgumentException implements ApiException
{
    public function __construct($message = "", Throwable $previous = null)
    {
        parent::__construct($message, 404, $previous);
    }
}
