<?php

namespace Test\Integration\UseCases;

use App\Application\UseCases\TodoListCreateCommand;
use App\Application\UseCases\TodoListCreateCommandHandler;
use Doctrine\DBAL\FetchMode;
use Test\Integration\ApplicationTestCase;

class TodoListCreateCommandHandlerTest extends ApplicationTestCase
{
    public function testTodoListCreatedOk()
    {
        $handler = new TodoListCreateCommandHandler();
        $handler->setTodoListRepository($this->repository);

        $id = $handler->handle(new TodoListCreateCommand('The Name', ['email1', 'email2']));

        $statement = $this->connection->executeQuery('SELECT * FROM `todo_list`');
        $items = $statement->fetchAll(FetchMode::ASSOCIATIVE);

        $this->assertCount(1, $items);
        $this->assertEquals($id, $items[0]['id']);
        $this->assertEquals('The Name', $items[0]['name']);
        $this->assertEquals(['email1', 'email2'], json_decode($items[0]['participants'], true));
    }
}
