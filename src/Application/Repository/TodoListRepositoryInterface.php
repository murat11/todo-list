<?php declare(strict_types=1);

namespace App\Application\Repository;

use App\Domain\TodoList;

interface TodoListRepositoryInterface
{
    /**
     * @param TodoList $todoList
     */
    public function addNew(TodoList $todoList): void;

    /**
     * @param string $listId
     */
    public function deleteById(string $listId): void;

    /**
     * @param string $listId
     *
     * @return TodoList
     */
    public function findOneById(string $listId): TodoList;
}
