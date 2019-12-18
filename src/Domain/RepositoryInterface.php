<?php declare(strict_types=1);

namespace App\Domain;


interface RepositoryInterface
{
    /**
     * @param $entity
     */
    public function addNew($entity): void;

    /**
     * @param $entity
     */
    public function save($entity): void;

    /**
     * @param string $listId
     */
    public function deleteById(string $listId): void;

    /**
     * @param string $listId
     *
     * @return mixed
     */
    public function findOneById(string $listId);
}
