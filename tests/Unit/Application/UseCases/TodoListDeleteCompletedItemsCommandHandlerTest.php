<?php

namespace Test\Unit\Application\UseCases;

use App\Application\Repository\TodoListRepositoryInterface;
use App\Application\UseCases\TodoListDeleteCompletedItems\TodoListDeleteCompletedItemsCommand;
use App\Application\UseCases\TodoListDeleteCompletedItems\TodoListDeleteCompletedItemsCommandHandler;
use App\Domain\TodoList;
use PHPUnit\Framework\TestCase;

class TodoListDeleteCompletedItemsCommandHandlerTest extends TestCase
{
    public function testHandleOk()
    {
        $command = $this->createMock(TodoListDeleteCompletedItemsCommand::class);
        $command->method('getListId')->willReturn('list-id');

        $todoList = $this->createMock(TodoList::class);
        $todoList->expects($this->once())->method('deleteCompletedItems');

        $repository = $this->createMock(TodoListRepositoryInterface::class);
        $repository->expects($this->once())->method('findOneById')->with('list-id')->willReturn($todoList);
        $repository->expects($this->once())->method('save')->with($todoList);

        $handler = new TodoListDeleteCompletedItemsCommandHandler();
        $handler->setTodoListRepository($repository);
        $handler->handle($command);
    }
}
