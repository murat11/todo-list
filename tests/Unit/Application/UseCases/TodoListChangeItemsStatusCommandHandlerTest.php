<?php

namespace Test\Unit\Application\UseCases;

use App\Application\UseCases\TodoListChangeItemsStatus\TodoListChangeItemsStatusCommand;
use App\Application\UseCases\TodoListChangeItemsStatus\TodoListChangeItemsStatusCommandHandler;
use App\Domain\TodoList\TodoList;
use App\Domain\TodoList\TodoListManager\TodoListManager;
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

        $manager = $this->createMock(TodoListManager::class);
        $manager->expects($this->once())->method('findTodoListById')->with('list-id')->willReturn($todoList);
        $manager->expects($this->once())->method('updateTodoList')->with($todoList);

        $handler = new TodoListChangeItemsStatusCommandHandler();
        $handler->setTodoListManager($manager);
        $handler->handle($command);
    }
}
