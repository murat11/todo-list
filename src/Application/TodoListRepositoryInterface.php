<?php declare(strict_types=1);

namespace App\Application;

use App\Domain\TodoList;

interface TodoListRepositoryInterface
{
    /**
     * @param TodoList $todoList
     */
    public function addNew(TodoList $todoList): void;
}
