<?php declare(strict_types=1);

namespace App\Domain\Exception;

use InvalidArgumentException;
use Throwable;

class TodoListItemNotFoundException extends InvalidArgumentException
{
    public function __construct(string $id, Throwable $previous = null)
    {
        parent::__construct(sprintf('Todo List Item with ID %s not found', $id), 404, $previous);
    }
}
