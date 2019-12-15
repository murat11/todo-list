<?php declare(strict_types=1);

namespace App\Infrastructure\Api\Exceptions;

use InvalidArgumentException;
use Throwable;

class BadRequestException extends InvalidArgumentException implements ApiException
{
    /**
     * @var array
     */
    private $errors;

    /**
     * @param array $errors
     * @param Throwable $previous
     */
    public function __construct(array $errors, Throwable $previous = null)
    {
        parent::__construct("Invalid input data", 400, $previous);
        $this->errors = $errors;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
