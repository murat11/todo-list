<?php

namespace Test\Unit\Application\UseCases;

use App\Application\Repository\TodoListRepositoryInterface;
use App\Application\UseCases\TodoListChangeItemsStatus\TodoListChangeItemsStatusCommand;
use App\Application\UseCases\TodoListChangeItemsStatus\TodoListChangeItemsStatusCommandHandler;
use App\Domain\TodoList;
use PHPUnit\Framework\TestCase;

class TodoListChangeItemsStatusCommandHandlerTest extends TestCase
{
    public function testHandleOk()
    {
        $command = $this->createMock(TodoListChangeItemsStatusCommand::class);
        $command->method('getListId')->willReturn('list-id');
        $command->method('isCompleted')->willReturn(true);

        $todoList = $this->createMock(TodoList::class);
        $todoList->expects($this->once())->method('applyNewStatusToAllItems')->with(true);

        $repository = $this->createMock(TodoListRepositoryInterface::class);
        $repository->expects($this->once())->method('findOneById')->with('list-id')->willReturn($todoList);
        $repository->expects($this->once())->method('save')->with($todoList);

        $handler = new TodoListChangeItemsStatusCommandHandler();
        $handler->setTodoListRepository($repository);
        $handler->handle($command);
    }
}
