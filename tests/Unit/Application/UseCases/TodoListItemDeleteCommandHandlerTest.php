<?php

namespace Test\Unit\Application\UseCases;

use App\Application\UseCases\TodoListItemDelete\TodoListItemDeleteCommand;
use App\Application\UseCases\TodoListItemDelete\TodoListItemDeleteCommandHandler;
use App\Domain\TodoList\TodoList;
use App\Domain\TodoList\TodoListManager\TodoListManager;
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

        $manager = $this->createMock(TodoListManager::class);
        $manager->expects($this->once())->method('findTodoListById')->with('list-id')->willReturn($todoList);
        $manager->expects($this->once())->method('updateTodoList')->with($todoList);

        $handler = new TodoListItemDeleteCommandHandler();
        $handler->setTodoListManager($manager);
        $handler->handle($command);
    }
}
