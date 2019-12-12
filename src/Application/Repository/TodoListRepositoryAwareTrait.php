<?php declare(strict_types=1);

namespace App\Application\Repository;

trait TodoListRepositoryAwareTrait
{
    /**
     * @var TodoListRepositoryInterface
     */
    private $todoListRepository;

    /**
     * @param TodoListRepositoryInterface $todoListRepository
     */
    public function setTodoListRepository(TodoListRepositoryInterface $todoListRepository)
    {
        $this->todoListRepository = $todoListRepository;
    }

}
