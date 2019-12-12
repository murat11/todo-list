<?php declare(strict_types=1);

namespace App\Application\Repository;

interface TodoListRepositoryAwareInterface
{
    /**
     * @param TodoListRepositoryInterface $todoListRepository
     */
    public function setTodoListRepository(TodoListRepositoryInterface $todoListRepository);
}
