<?php

namespace Test\Unit\Application\UseCases;

use App\Application\UseCases\TodoListDelete\TodoListDeleteCommand;
use App\Application\UseCases\TodoListDelete\TodoListDeleteCommandHandler;
use App\Domain\TodoList\TodoList;
use App\Domain\TodoList\TodoListManager\TodoListManager;
use PHPUnit\Framework\TestCase;

class TodoListDeleteCommandHandlerTest extends TestCase
{
    public function testHandleOk()
    {
        $todoList = $this->createMock(TodoList::class);

        $command = $this->createMock(TodoListDeleteCommand::class);
        $command->expects($this->once())->method('getListId')->willReturn('id-to-delete');

        $manager = $this->createMock(TodoListManager::class);
        $manager->expects($this->once())->method('findTodoListById')->with('id-to-delete')->willReturn($todoList);
        $manager->expects($this->once())->method('deleteTodoList')->with($todoList);


        $handler = new TodoListDeleteCommandHandler();
        $handler->setTodoListManager($manager);
        $handler->handle($command);
    }
}
