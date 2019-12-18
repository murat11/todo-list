<?php

namespace Test\Unit\Application\UseCases;

use App\Application\UseCases\TodoListDeleteCompletedItems\TodoListDeleteCompletedItemsCommand;
use App\Application\UseCases\TodoListDeleteCompletedItems\TodoListDeleteCompletedItemsCommandHandler;
use App\Domain\TodoList\TodoList;
use App\Domain\TodoList\TodoListManager\TodoListManager;
use PHPUnit\Framework\TestCase;

class TodoListDeleteCompletedItemsCommandHandlerTest extends TestCase
{
    public function testHandleOk()
    {
        $command = $this->createMock(TodoListDeleteCompletedItemsCommand::class);
        $command->method('getListId')->willReturn('list-id');

        $todoList = $this->createMock(TodoList::class);
        $todoList->expects($this->once())->method('deleteCompletedItems');

        $manager = $this->createMock(TodoListManager::class);
        $manager->expects($this->once())->method('findTodoListById')->with('list-id')->willReturn($todoList);
        $manager->expects($this->once())->method('updateTodoList')->with($todoList);

        $handler = new TodoListDeleteCompletedItemsCommandHandler();
        $handler->setTodoListManager($manager);
        $handler->handle($command);
    }
}
