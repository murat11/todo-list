<?php declare(strict_types=1);

namespace App\Domain\Exception;

use Doctrine\DBAL\Exception\InvalidArgumentException;
use Throwable;

class TodoListNotFoundException extends InvalidArgumentException
{
    /**
     * @param string $id
     * @param Throwable $previous
     */
    public function __construct(string $id, Throwable $previous = null)
    {
        parent::__construct(sprintf('Can not find Todo List with ID "%s" ', $id), 404, $previous);
    }
}
