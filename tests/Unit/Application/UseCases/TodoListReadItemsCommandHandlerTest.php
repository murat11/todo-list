<?php

namespace Test\Unit\Application\UseCases;

use App\Application\UseCases\TodoListReadItems\TodoListReadItemsCommand;
use App\Application\UseCases\TodoListReadItems\TodoListReadItemsCommandHandler;
use App\Domain\TodoList\TodoList;
use App\Domain\TodoList\TodoListManager\TodoListManager;
use PHPUnit\Framework\TestCase;

class TodoListReadItemsCommandHandlerTest extends TestCase
{
    public function testHandleOk()
    {
        $todoList = $this->createMock(TodoList::class);
        $todoList->expects($this->once())->method('getItems')->willReturn(['list-of-todo-items']);

        $command = $this->createMock(TodoListReadItemsCommand::class);
        $command->expects($this->once())->method('getListId')->willReturn('list-id');

        $manager = $this->createMock(TodoListManager::class);
        $manager->expects($this->once())->method('findTodoListById')->with('list-id')->willReturn($todoList);

        $handler = new TodoListReadItemsCommandHandler();
        $handler->setTodoListManager($manager);
        $this->assertEquals(['list-of-todo-items'], $handler->handle($command));
    }
}
