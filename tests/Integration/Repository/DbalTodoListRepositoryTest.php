<?php

namespace Test\Integration\Repository;

use App\Domain\TodoList;
use App\Domain\TodoListItem;
use Doctrine\DBAL\FetchMode;
use Test\Integration\ApplicationTestCase;

class DbalTodoListRepositoryTest extends ApplicationTestCase
{
    public function testTodoListCreatedOk()
    {
        $todoListItem = new TodoListItem();
        $todoListItem->setTitle('Item1');
        $todoListItem->setCompleted(false);

        $todoList = new TodoList();
        $todoList->setName('The Name');
        $todoList->addItem($todoListItem);

        $this->assertEmpty($todoList->getId());
        $this->assertEmpty($todoListItem->getId());

        $this->repository->addNew($todoList);

        $this->assertNotEmpty($todoList->getId());
        $this->assertNotEmpty($todoListItem->getId());

        $statement = $this->connection->executeQuery('SELECT * FROM `todo_list`');
        $todoListsFromDb = $statement->fetchAll(FetchMode::ASSOCIATIVE);

        $this->assertCount(1, $todoListsFromDb);
        $itemsFromDb = json_decode($todoListsFromDb[0]['items'], true);
        $this->assertEquals($todoListItem->getId(), $itemsFromDb[0]['id']);
        $this->assertEquals($todoListItem->getTitle(), $itemsFromDb[0]['title']);
        $this->assertEquals($todoListItem->isCompleted(), $itemsFromDb[0]['is_completed']);

        $todoList->deleteItemById($todoListItem->getId());

        $this->repository->save($todoList);

        $statement = $this->connection->executeQuery('SELECT * FROM `todo_list`');
        $todoListsFromDb = $statement->fetchAll(FetchMode::ASSOCIATIVE);

        $this->assertCount(1, $todoListsFromDb);
        $itemsFromDb = json_decode($todoListsFromDb[0]['items'], true);
        $this->assertEmpty($itemsFromDb);
    }
}
