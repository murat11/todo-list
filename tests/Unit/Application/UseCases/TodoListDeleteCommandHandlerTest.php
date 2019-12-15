<?php

namespace Test\Unit\Application\UseCases;

use App\Application\Repository\TodoListRepositoryInterface;
use App\Application\UseCases\TodoListDelete\TodoListDeleteCommand;
use App\Application\UseCases\TodoListDelete\TodoListDeleteCommandHandler;
use App\Domain\EventManager\EventManagerInterface;
use App\Domain\Events\TodoListDeletedEvent;
use App\Domain\TodoList;
use PHPUnit\Framework\TestCase;

class TodoListDeleteCommandHandlerTest extends TestCase
{
    public function testHandleOk()
    {
        $todoList = $this->createMock(TodoList::class);

        $command = $this->createMock(TodoListDeleteCommand::class);
        $command->expects($this->once())->method('getListId')->willReturn('id-to-delete');

        $repository = $this->createMock(TodoListRepositoryInterface::class);
        $repository->expects($this->once())->method('deleteById')->with('id-to-delete');
        $repository->expects($this->once())->method('findOneById')->with('id-to-delete')->willReturn($todoList);

        $eventManager = $this->createMock(EventManagerInterface::class);
        $eventManager->expects($this->once())->method('emitEvent')->with(new TodoListDeletedEvent($todoList));

        $handler = new TodoListDeleteCommandHandler();
        $handler->setTodoListRepository($repository);
        $handler->setEventManager($eventManager);
        $handler->handle($command);
    }
}
