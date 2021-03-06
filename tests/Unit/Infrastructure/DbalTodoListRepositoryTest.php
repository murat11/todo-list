<?php

namespace Test\Unit\Infrastructure;

use App\Domain\TodoList\TodoList;
use App\Domain\TodoList\TodoListItem;
use App\Infrastructure\Repository\TodoListDbalRepository;
use App\Infrastructure\Framework\IdGenerator\IdGeneratorInterface;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\ResultStatement;
use Doctrine\DBAL\Types\Types;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class DbalTodoListRepositoryTest extends TestCase
{

    /**
     * @var Connection|MockObject
     */
    private $connection;

    /**
     * @var TodoListDbalRepository
     */
    private $repository;

    /**
     * @var IdGeneratorInterface|MockObject
     */
    private $idGenerator;

    public function setUp(): void
    {
        $this->connection = $this->createMock(Connection::class);
        $this->idGenerator = $this->createMock(IdGeneratorInterface::class);
        $this->repository = new TodoListDbalRepository($this->connection, $this->idGenerator);
    }

    public function testItIsInitialized()
    {
        $this->assertInstanceOf(TodoListDbalRepository::class, $this->repository);
    }

    public function testAddNewTodoListOk()
    {
        $this->idGenerator->expects($this->once())->method('generateId')->willReturn('todo-list-id');
        $this->connection->expects($this->once())->method('insert')
            ->with(
                'todo_list',
                [
                    'id' => 'todo-list-id',
                    'name' => 'Todo List Name',
                    'participants' => ['email1', 'email2'],
                    'items' => [],
                ],
                [
                    'id' => Types::STRING,
                    'name' => Types::STRING,
                    'participants' => Types::JSON,
                    'items' => Types::JSON,
                ]
            );


        $todoList = new TodoList();
        $todoList->setName('Todo List Name');
        $todoList->addParticipantEmail('email1');
        $todoList->addParticipantEmail('email2');
        $this->assertEmpty($todoList->getId());

        $this->repository->addNew($todoList);
        $this->assertEquals('todo-list-id', $todoList->getId());
    }

    public function testSaveOk()
    {
        $this->idGenerator->expects($this->once())->method('generateId')->willReturn('generated-item-id');
        $this->connection->expects($this->once())->method('update')
            ->with(
                'todo_list',
                [
                    'name' => 'Todo List Name',
                    'participants' => ['email1', 'email2'],
                    'items' => [
                        [
                            'id' => 'old-item-id',
                            'title' => 'Todo List Item Title 1',
                            'is_completed' => true,
                        ],
                        [
                            'id' => 'generated-item-id',
                            'title' => 'Todo List Item Title 2',
                            'is_completed' => false,
                        ],
                    ],
                ],
                ['id' => 'todo-list-id'],
                [
                    'id' => Types::STRING,
                    'name' => Types::STRING,
                    'participants' => Types::JSON,
                    'items' => Types::JSON,
                ]
            );


        $todoList = new TodoList();
        $todoList->setName('Todo List Name');
        $todoList->addParticipantEmail('email1');
        $todoList->addParticipantEmail('email2');
        $this->setEntityId($todoList, 'todo-list-id');

        $todoListItem1 = (new TodoListItem())->setTitle('Todo List Item Title 1')->setCompleted(true);
        $this->setEntityId($todoListItem1, 'old-item-id');
        $todoList->addItem($todoListItem1);

        $todoListItem2 = (new TodoListItem())->setTitle('Todo List Item Title 2')->setCompleted(false);
        $todoList->addItem($todoListItem2);

        $this->repository->save($todoList);

        $this->assertEquals('old-item-id', $todoListItem1->getId());
        $this->assertEquals('generated-item-id', $todoListItem2->getId());
    }

    public function testFindByIdOk()
    {
        $statement = $this->createMock(ResultStatement::class);
        $statement->expects($this->once())->method('fetch')->willReturn(
            [
                'id' => 'todo-list-id',
                'name' => 'Todo List Name',
                'participants' => '["email1", "email2"]',
                'items' => json_encode(
                    [
                        [
                            'id' => 'todo-list-item-id-1',
                            'title' => 'Todo List Item Title 1',
                            'is_completed' => true,
                        ],
                        [
                            'id' => 'todo-list-item-id-2',
                            'title' => 'Todo List Item Title 2',
                            'is_completed' => false,
                        ],
                    ]
                )
            ]
        );
        $this->connection->method('quoteIdentifier')
            ->willReturnCallback(
                function (string $str) {
                    return "`$str`";
                }
            );
        $this->connection->method('quote')
            ->willReturnCallback(
                function (string $str) {
                    return "'$str'";
                }
            );
        $this->connection->expects($this->once())->method('executeQuery')
            ->with(
                'SELECT * FROM `todo_list` WHERE `id` = :idValue LIMIT 1',
                [
                    'idValue' => "todo-list-id",
                ],
                [
                    'idValue' => Types::STRING,
                ]
            )->willReturn($statement);

        $todoList = $this->repository->findOneById('todo-list-id');
        $this->assertInstanceOf(TodoList::class, $todoList);
        $this->assertEquals('todo-list-id', $todoList->getId());
        $this->assertEquals(['email1', 'email2'], $todoList->getParticipantEmails());
        $todoListItems = $todoList->getItems();
        $this->assertCount(2, $todoListItems);
        $this->assertInstanceOf(TodoListItem::class, $todoListItems[0]);
        $this->assertEquals('todo-list-item-id-1', $todoListItems[0]->getId());
        $this->assertEquals('Todo List Item Title 1', $todoListItems[0]->getTitle());
        $this->assertFalse($todoListItems[1]->isCompleted());
    }

    public function testFindByIdNotFound()
    {
        $statement = $this->createMock(ResultStatement::class);
        $statement->expects($this->once())->method('fetch')->willReturn(false);
        $this->connection->expects($this->once())->method('executeQuery')->willReturn($statement);

        $this->assertNull($this->repository->findOneById('some-id'));
    }

    public function testDeleteByIdOk()
    {
        $this->connection->expects($this->once())->method('delete')
            ->with('todo_list', ['id' => 'id-to-delete'], ['id' => Types::STRING])
            ->willReturn(1);
        $this->repository->deleteById('id-to-delete');
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
}
