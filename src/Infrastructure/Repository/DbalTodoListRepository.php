<?php declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Application\Repository\TodoListRepositoryInterface;
use App\Domain\TodoList;
use App\Domain\TodoListItem;
use App\Infrastructure\Repository\Exception\NotFoundException;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Types\Types;
use ReflectionClass;

class DbalTodoListRepository implements TodoListRepositoryInterface
{
    private const TABLE = 'todo_list';

    private const MAPPING = [
        TodoList::class => [
            'id' => ['column' => 'id', 'column_type' => Types::STRING],
            'name' => ['column' => 'name', 'column_type' => Types::STRING],
            'participantEmails' => ['column' => 'participants', 'column_type' => Types::JSON],
            'items' => [
                'column' => 'items',
                'column_type' => Types::JSON,
                'serializer' => 'serializeTodoListItems',
                'deserializer' => 'deserializeTodoListItems',
            ],
        ],
        TodoListItem::class => [
            'id' => ['column' => 'id', 'column_type' => Types::STRING],
            'title' => ['column' => 'title', 'column_type' => Types::STRING],
            'completed' => ['column' => 'is_completed', 'column_type' => Types::BOOLEAN],
        ],
    ];

    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var IdGeneratorInterface
     */
    private $idGenerator;

    /**
     * @param Connection $connection
     * @param IdGeneratorInterface $idGenerator
     */
    public function __construct(Connection $connection, IdGeneratorInterface $idGenerator)
    {
        $this->connection = $connection;
        $this->idGenerator = $idGenerator;
    }

    /**
     * @param TodoList $todoList
     */
    public function addNew(TodoList $todoList): void
    {
        $id = $this->generateId();
        $mappedInstance = $this->map($todoList);
        $mappedInstance['values']['id'] = $id;
        $this->connection->insert(
            self::TABLE,
            $mappedInstance['values'],
            $mappedInstance['types']
        );
        $this->setEntityId($todoList, $id);
    }

    /**
     * @param TodoList $todoList
     */
    public function save(TodoList $todoList): void
    {
        $mappingForId = self::MAPPING[TodoList::class]['id'];
        $mappedInstance = $this->map($todoList);
        $identifier[$mappingForId['column']] = $mappedInstance['values'][$mappingForId['column']];
        unset($mappedInstance['values'][$mappingForId['column']]);
        $this->connection->update(
            self::TABLE,
            $mappedInstance['values'],
            $identifier,
            $mappedInstance['types']
        );
    }

    /**
     * @param string $listId
     */
    public function deleteById(string $listId): void
    {
        $mappingForId = self::MAPPING[TodoList::class]['id'];
        $result = $this->connection->delete(
            self::TABLE,
            [$mappingForId['column'] => $listId],
            [$mappingForId['column'] => $mappingForId['column_type']]
        );

        if (!$result) {
            throw new NotFoundException(self::TABLE, $listId);
        }
    }

    /**
     * @param string $listId
     *
     * @return TodoList
     */
    public function findOneById(string $listId): TodoList
    {
        $mappingForId = self::MAPPING[TodoList::class]['id'];
        $st = $this->connection->executeQuery(
            'SELECT * FROM :table WHERE :idColumn = :idValue LIMIT 1',
            [
                'table' => $this->connection->quoteIdentifier(self::TABLE),
                'idColumn' => $this->connection->quoteIdentifier($mappingForId['column']),
                'idValue' => $this->connection->quote($listId, $mappingForId['column_type']),
            ]
        );

        $row = $st->fetch(FetchMode::ASSOCIATIVE);
        if (empty($row)) {
            throw new NotFoundException(self::TABLE, $listId);
        }

        $todoList = $this->unmap($row, TodoList::class);

        return $todoList;
    }

    /**
     * @return string
     */
    private function generateId(): string
    {
        return $this->idGenerator->generateId();
    }

    /**
     * @param $entity
     * @param string $id
     */
    private function setEntityId($entity, string $id): void
    {
        $entityClass = get_class($entity);
        $reflectionClass = new ReflectionClass($entityClass);
        $reflectionProperty = $reflectionClass->getProperty('id');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($entity, $id);
    }

    /**
     * @param $entity
     *
     * @return array
     */
    private function map($entity): array
    {

        $entityClass = get_class($entity);
        $mappingProperties = self::MAPPING[$entityClass];
        $reflectionClass = new ReflectionClass($entityClass);

        $result = [];
        foreach ($mappingProperties as $propertyName => $columnSettings) {
            $reflectionProperty = $reflectionClass->getProperty($propertyName);
            $reflectionProperty->setAccessible(true);
            $value = $reflectionProperty->getValue($entity);
            if (!empty($columnSettings['serializer'])) {
                $value = call_user_func([$this, $columnSettings['serializer']], $value);
            }

            $result['values'][$columnSettings['column']] = $value;
            $result['types'][$columnSettings['column']] = $columnSettings['column_type'];
        }

        return $result;
    }

    /**
     * @param array $row
     * @param string $entityClass
     *
     * @return mixed
     */
    private function unmap(array $row, string $entityClass)
    {
        $entity = new $entityClass;
        $mappingProperties = self::MAPPING[$entityClass];

        $reflectionClass = new ReflectionClass($entityClass);
        foreach ($mappingProperties as $propertyName => $columnSettings) {
            $reflectionProperty = $reflectionClass->getProperty($propertyName);
            $reflectionProperty->setAccessible(true);
            $value = $row[$columnSettings['column']];
            if (!empty($columnSettings['deserializer'])) {
                $value = call_user_func([$this, $columnSettings['deserializer']], $value);
            }
            $reflectionProperty->setValue($entity, $value);
        }

        return $entity;
    }

    /**
     * @param TodoListItem[] $todoListItems
     *
     * @return array
     */
    private function serializeTodoListItems(array $todoListItems): array
    {
        $mappingForId = self::MAPPING[TodoListItem::class]['id'];

        $result = [];
        foreach ($todoListItems as &$todoListItem) {
            $serializedItem = $this->map($todoListItem)['values'];
            if (empty($serializedItem[$mappingForId['column']])) {
                $todoListItemId = $this->generateId();
                $serializedItem[$mappingForId['column']] = $todoListItemId;
                $this->setEntityId($todoListItem, $todoListItemId);
            }

            $result[] = $serializedItem;
        }

        return $result;
    }

    /**
     * @param array $serializedItems
     *
     * @return TodoListItem[]
     */
    private function deserializeTodoListItems(array $serializedItems): array
    {
        $result = [];
        foreach ($serializedItems as $serializedItem) {
            $result[] = $this->unmap($serializedItem, TodoListItem::class);
        }

        return $result;
    }
}
