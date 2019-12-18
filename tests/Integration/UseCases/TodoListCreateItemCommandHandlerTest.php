<?php

namespace Test\Integration\UseCases;

use App\Application\UseCases\TodoListCreateItem\TodoListCreateItemCommand;
use App\Application\UseCases\TodoListCreateItem\TodoListCreateItemCommandHandler;
use App\Domain\TodoList\TodoListManager\TodoListManager;
use App\Infrastructure\EventManager\EventManager;
use Doctrine\DBAL\FetchMode;
use Test\Integration\ApplicationTestCase;

class TodoListCreateItemCommandHandlerTest extends ApplicationTestCase
{
    public function testTodoListCreatedOk()
    {
        $this->connection->insert(
            'todo_list',
            [
                'id' => '252a3d9e-5a52-48f6-93c2-9c5d1f1e6009',
                'name' => 'The Name',
            ]
        );

        $handler = new TodoListCreateItemCommandHandler();
        $handler->setTodoListManager(new TodoListManager($this->repository, new EventManager()));

        $todoListItem = $handler->handle(
            new TodoListCreateItemCommand(
                '252a3d9e-5a52-48f6-93c2-9c5d1f1e6009',
                'List Item',
                true
            )
        );

        $statement = $this->connection->executeQuery('SELECT * FROM `todo_list`');
        $items = $statement->fetchAll(FetchMode::ASSOCIATIVE);

        $this->assertCount(1, $items);
        $itemsFromDb = json_decode($items[0]['items'], true);
        $this->assertEquals($todoListItem->getId(), $itemsFromDb[0]['id']);
        $this->assertEquals($todoListItem->getTitle(), $itemsFromDb[0]['title']);
        $this->assertEquals($todoListItem->isCompleted(), $itemsFromDb[0]['is_completed']);
    }
}
