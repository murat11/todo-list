<?php

namespace Test\Unit\Application\UseCases;

use App\Application\Repository\TodoListRepositoryInterface;
use App\Application\UseCases\TodoListCreate\TodoListCreateCommand;
use App\Application\UseCases\TodoListCreate\TodoListCreateCommandHandler;
use App\Domain\EventManager\EventManagerInterface;
use App\Domain\Events\TodoListCreatedEvent;
use App\Domain\TodoList;
use PHPUnit\Framework\TestCase;

class TodoListCreateCommandHandlerTest extends TestCase
{
    public function testHandleOk()
    {
        $todoList = $this->createMock(TodoList::class);

        $command = $this->createMock(TodoListCreateCommand::class);
        $command->method('buildTodoListInstance')->willReturn($todoList);

        $repository = $this->createMock(TodoListRepositoryInterface::class);
        $repository->expects($this->once())->method('addNew')->with($todoList);

        $eventManager = $this->createMock(EventManagerInterface::class);
        $eventManager->expects($this->once())->method('emitEvent')->with(new TodoListCreatedEvent($todoList));

        $handler = new TodoListCreateCommandHandler();
        $handler->setTodoListRepository($repository);
        $handler->setEventManager($eventManager);
        $this->assertEquals($todoList, $handler->handle($command));
    }
}
