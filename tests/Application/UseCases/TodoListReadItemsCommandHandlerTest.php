<?php

namespace Test\Application\UseCases;

use App\Application\TodoListRepositoryInterface;
use App\Application\UseCases\TodoListReadItemsCommand;
use App\Application\UseCases\TodoListReadItemsCommandHandler;
use App\Domain\TodoList;
use PHPUnit\Framework\TestCase;

class TodoListReadItemsCommandHandlerTest extends TestCase
{
    public function testHandleOk()
    {
        $todoList = $this->createMock(TodoList::class);
        $todoList->expects($this->once())->method('getItems')->willReturn(['list-of-todo-items']);

        $repository = $this->createMock(TodoListRepositoryInterface::class);
        $repository->expects($this->once())->method('findOneById')->with('list-id')->willReturn($todoList);

        $command = $this->createMock(TodoListReadItemsCommand::class);
        $command->expects($this->once())->method('getListId')->willReturn('list-id');

        $handler = new TodoListReadItemsCommandHandler($repository);
        $this->assertEquals(['list-of-todo-items'], $handler->handle($command));
    }
}
