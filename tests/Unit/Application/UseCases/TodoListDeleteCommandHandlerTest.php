<?php

namespace Test\Unit\Application\UseCases;

use App\Application\Repository\TodoListRepositoryInterface;
use App\Application\UseCases\TodoListDeleteCommand;
use App\Application\UseCases\TodoListDeleteCommandHandler;
use PHPUnit\Framework\TestCase;

class TodoListDeleteCommandHandlerTest extends TestCase
{
    public function testHandleOk()
    {
        $command = $this->createMock(TodoListDeleteCommand::class);
        $command->expects($this->once())->method('getListId')->willReturn('id-to-delete');

        $repository = $this->createMock(TodoListRepositoryInterface::class);
        $repository->expects($this->once())->method('deleteById')->with('id-to-delete');

        $handler = new TodoListDeleteCommandHandler();
        $handler->setTodoListRepository($repository);
        $handler->handle($command);
    }
}
