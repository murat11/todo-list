<?php

namespace Test\Application\UseCases;

use App\Application\TodoListRepositoryInterface;
use App\Application\UseCases\TodoListCreateCommand;
use App\Application\UseCases\TodoListCreateCommandHandler;
use App\Domain\TodoList;
use PHPUnit\Framework\TestCase;

class TodoListCreateCommandHandlerTest extends TestCase
{
    public function testHandleOk()
    {
        $todoList = $this->createMock(TodoList::class);
        $todoList->expects($this->once())->method('getId')->willReturn('some-id');

        $command = $this->createMock(TodoListCreateCommand::class);
        $command->method('buildTodoListInstance')->willReturn($todoList);

        $repository = $this->createMock(TodoListRepositoryInterface::class);
        $repository->expects($this->once())->method('addNew')->with($todoList);

        $handler = new TodoListCreateCommandHandler($repository);
        $this->assertEquals('some-id', $handler->handle($command));
    }
}
