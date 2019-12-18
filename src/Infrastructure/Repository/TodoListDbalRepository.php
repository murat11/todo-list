<?php declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\RepositoryInterface;
use App\Domain\TodoList\TodoList;
use App\Domain\TodoList\TodoListItem;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Types\Types;
use ReflectionClass;

class TodoListDbalRepository implements RepositoryInterface
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
     * @param TodoList $entity
     */
    public function addNew($entity): void
    {
        $id = $this->generateId();
        $mappedInstance = $this->map($entity);
        $mappedInstance['values']['id'] = $id;
        $this->connection->insert(
            self::TABLE,
            $mappedInstance['values'],
            $mappedInstance['types']
        );
        $this->setEntityId($entity, $id);
    }

    /**
     * @param TodoList $todoList
     */
    public function save($todoList): void
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
        $this->connection->delete(
            self::TABLE,
            [$mappingForId['column'] => $listId],
            [$mappingForId['column'] => $mappingForId['column_type']]
        );
    }

    /**
     * @param string $listId
     *
     * @return TodoList
     */
    public function findOneById(string $listId)
    {
        $mappingForId = self::MAPPING[TodoList::class]['id'];
        $query = sprintf(
            'SELECT * FROM %s WHERE %s = :idValue LIMIT 1',
            $this->connection->quoteIdentifier(self::TABLE),
            $this->connection->quoteIdentifier($mappingForId['column'])
        );
        $st = $this->connection->executeQuery(
            $query,
            [
                'idValue' => $listId,
            ],
            [
                'idValue' => $mappingForId['column_type']
            ]
        );

        $row = $st->fetch(FetchMode::ASSOCIATIVE);
        if (empty($row)) {
            return null;
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

            if (!empty($value) && $columnSettings['column_type'] === Types::JSON) {
                $value = json_decode($value, true);
            }

            if (!empty($value) && !empty($columnSettings['deserializer'])) {
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

            //generate list item id if it's empty
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
