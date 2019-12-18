<?php

namespace Test\Unit\Application\UseCases;

use App\Application\UseCases\TodoListItemUpdate\TodoListItemUpdateCommand;
use App\Application\UseCases\TodoListItemUpdate\TodoListItemUpdateCommandHandler;
use App\Domain\TodoList\TodoList;
use App\Domain\TodoList\TodoListItem;
use App\Domain\TodoList\TodoListManager\TodoListManager;
use PHPUnit\Framework\TestCase;

class TodoListItemUpdateCommandHandlerTest extends TestCase
{
    public function testHandleOk()
    {
        $command = $this->createMock(TodoListItemUpdateCommand::class);
        $command->method('getListId')->willReturn('list-id');
        $command->method('getListItemId')->willReturn('list-item-id');
        $command->method('getTitle')->willReturn('list-item-title');
        $command->method('isCompleted')->willReturn(false);

        $todoListItem = $this->createMock(TodoListItem::class);
        $todoListItem->expects($this->once())->method('setCompleted')->with(false);
        $todoListItem->expects($this->once())->method('setTitle')->with('list-item-title');

        $todoList = $this->createMock(TodoList::class);
        $todoList->expects($this->once())->method('getItemById')->with('list-item-id')->willReturn($todoListItem);

        $manager = $this->createMock(TodoListManager::class);
        $manager->expects($this->once())->method('findTodoListById')->with('list-id')->willReturn($todoList);
        $manager->expects($this->once())->method('updateTodoList')->with($todoList);

        $handler = new TodoListItemUpdateCommandHandler();
        $handler->setTodoListManager($manager);
        $this->assertEquals($todoListItem, $handler->handle($command));
    }
}
