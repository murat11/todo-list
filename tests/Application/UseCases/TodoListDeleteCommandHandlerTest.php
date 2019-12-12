<?php

namespace Test\Application\UseCases;

use App\Application\TodoListRepositoryInterface;
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

        (new TodoListDeleteCommandHandler($repository))->handle($command);
    }
}
