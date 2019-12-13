<?php

namespace Test\Unit\Application\UseCases;

use App\Application\Repository\TodoListRepositoryInterface;
use App\Application\UseCases\TodoListCreateItemCommand;
use App\Application\UseCases\TodoListCreateItemCommandHandler;
use App\Domain\TodoList;
use App\Domain\TodoListItem;
use PHPUnit\Framework\TestCase;

class TodoListCreateItemCommandHandlerTest extends TestCase
{
    public function testHandleOk()
    {
        $todoListItem = $this->createMock(TodoListItem::class);

        $command = $this->createMock(TodoListCreateItemCommand::class);
        $command->method('buildTodoListItem')->willReturn($todoListItem);
        $command->method('getListId')->willReturn('list-id');

        $todoList = $this->createMock(TodoList::class);
        $todoList->expects($this->once())->method('addItem')->with($todoListItem);

        $repository = $this->createMock(TodoListRepositoryInterface::class);
        $repository->expects($this->once())->method('findOneById')->with('list-id')->willReturn($todoList);
        $repository->expects($this->once())->method('save')->with($todoList);

        $handler = new TodoListCreateItemCommandHandler();
        $handler->setTodoListRepository($repository);
        $this->assertEquals($todoListItem, $handler->handle($command));
    }
}
