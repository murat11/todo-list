<?php

namespace Test\Application\UseCases;

use App\Application\Repository\TodoListRepositoryInterface;
use App\Application\UseCases\TodoListItemDeleteCommand;
use App\Application\UseCases\TodoListItemDeleteCommandHandler;
use App\Domain\TodoList;
use PHPUnit\Framework\TestCase;

class TodoListItemDeleteCommandHandlerTest extends TestCase
{
    public function testHandleOk()
    {
        $command = $this->createMock(TodoListItemDeleteCommand::class);
        $command->method('getListId')->willReturn('list-id');
        $command->method('getListItemId')->willReturn('list-item-id');

        $todoList = $this->createMock(TodoList::class);
        $todoList->expects($this->once())->method('deleteItemById')->with('list-item-id');

        $repository = $this->createMock(TodoListRepositoryInterface::class);
        $repository->expects($this->once())->method('findOneById')->with('list-id')->willReturn($todoList);
        $repository->expects($this->once())->method('save')->with($todoList);

        $handler = new TodoListItemDeleteCommandHandler();
        $handler->setTodoListRepository($repository);
        $handler->handle($command);
    }
}
