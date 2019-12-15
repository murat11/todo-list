<?php

namespace Test\Unit\Application\UseCases;

use App\Application\Repository\TodoListRepositoryInterface;
use App\Application\UseCases\TodoListCreate\TodoListCreateCommand;
use App\Application\UseCases\TodoListCreate\TodoListCreateCommandHandler;
use App\Domain\TodoList;
use PHPUnit\Framework\TestCase;

class TodoListCreateCommandHandlerTest extends TestCase
{
    public function testHandleOk()
    {
        $todoList = $this->createMock(TodoList::class);

        $command = $this->createMock(TodoListCreateCommand::class);
        $command->method('buildTodoListInstance')->willReturn($todoList);

        $repository = $this->createMock(TodoListRepositoryInterface::class);
        $repository->expects($this->once())->method('addNew')->with($todoList);

        $handler = new TodoListCreateCommandHandler();
        $handler->setTodoListRepository($repository);
        $this->assertEquals($todoList, $handler->handle($command));
    }
}
