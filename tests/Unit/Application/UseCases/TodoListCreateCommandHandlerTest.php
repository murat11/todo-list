<?php

namespace Test\Unit\Application\UseCases;

use App\Application\UseCases\TodoListCreate\TodoListCreateCommand;
use App\Application\UseCases\TodoListCreate\TodoListCreateCommandHandler;
use App\Domain\TodoList\TodoList;
use App\Domain\TodoList\TodoListManager\TodoListManager;
use PHPUnit\Framework\TestCase;

class TodoListCreateCommandHandlerTest extends TestCase
{
    public function testHandleOk()
    {
        $todoList = $this->createMock(TodoList::class);

        $command = $this->createMock(TodoListCreateCommand::class);
        $command->method('buildTodoListInstance')->willReturn($todoList);

        $manager = $this->createMock(TodoListManager::class);
        $manager->expects($this->once())->method('addNewTodoList')->with($todoList);

        $handler = new TodoListCreateCommandHandler();
        $handler->setTodoListManager($manager);
        $this->assertEquals($todoList, $handler->handle($command));
    }
}
