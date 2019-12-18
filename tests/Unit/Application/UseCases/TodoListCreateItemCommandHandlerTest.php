<?php

namespace Test\Unit\Application\UseCases;

use App\Application\UseCases\TodoListCreateItem\TodoListCreateItemCommand;
use App\Application\UseCases\TodoListCreateItem\TodoListCreateItemCommandHandler;
use App\Domain\TodoList\TodoList;
use App\Domain\TodoList\TodoListItem;
use App\Domain\TodoList\TodoListManager\TodoListManager;
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

        $manager = $this->createMock(TodoListManager::class);
        $manager->expects($this->once())->method('findTodoListById')->with('list-id')->willReturn($todoList);
        $manager->expects($this->once())->method('updateTodoList')->with($todoList);

        $handler = new TodoListCreateItemCommandHandler();
        $handler->setTodoListManager($manager);
        $this->assertEquals($todoListItem, $handler->handle($command));
    }
}
