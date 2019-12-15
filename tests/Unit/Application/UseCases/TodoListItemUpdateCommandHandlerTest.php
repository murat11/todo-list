<?php

namespace Test\Unit\Application\UseCases;

use App\Application\Repository\TodoListRepositoryInterface;
use App\Application\UseCases\TodoListItemUpdate\TodoListItemUpdateCommand;
use App\Application\UseCases\TodoListItemUpdate\TodoListItemUpdateCommandHandler;
use App\Domain\TodoList;
use App\Domain\TodoListItem;
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

        $repository = $this->createMock(TodoListRepositoryInterface::class);
        $repository->expects($this->once())->method('findOneById')->with('list-id')->willReturn($todoList);
        $repository->expects($this->once())->method('save')->with($todoList);

        $handler = new TodoListItemUpdateCommandHandler();
        $handler->setTodoListRepository($repository);
        $this->assertEquals($todoListItem, $handler->handle($command));
    }
}
