<?php

namespace Test\Unit\Domain;

use App\Domain\EventManager\EventManagerInterface;
use App\Domain\RepositoryInterface;
use App\Domain\TodoList\Events\TodoListCreatedEvent;
use App\Domain\TodoList\Events\TodoListDeletedEvent;
use App\Domain\TodoList\Exception\TodoListNotFoundException;
use App\Domain\TodoList\TodoList;
use App\Domain\TodoList\TodoListManager\TodoListManager;
use PHPUnit\Framework\TestCase;

class TodoListManagerTest extends TestCase
{
    public function testAddNewTodoList()
    {
        $todoList = $this->createMock(TodoList::class);

        $repository = $this->createMock(RepositoryInterface::class);
        $repository->expects($this->once())->method('addNew')->with($todoList);

        $eventManager = $this->createMock(EventManagerInterface::class);
        $eventManager->expects($this->once())->method('emitEvent')->with(new TodoListCreatedEvent($todoList));

        $todoListManager = new TodoListManager($repository, $eventManager);
        $todoListManager->addNewTodoList($todoList);
    }

    public function testUpdateTodoList()
    {
        $todoList = $this->createMock(TodoList::class);

        $repository = $this->createMock(RepositoryInterface::class);
        $repository->expects($this->once())->method('save')->with($todoList);

        $eventManager = $this->createMock(EventManagerInterface::class);

        $todoListManager = new TodoListManager($repository, $eventManager);
        $todoListManager->updateTodoList($todoList);
    }

    public function testFindTodoListById()
    {
        $todoList = $this->createMock(TodoList::class);

        $repository = $this->createMock(RepositoryInterface::class);
        $repository->expects($this->once())->method('findOneById')
            ->with('todo-list-id')->willReturn($todoList);

        $eventManager = $this->createMock(EventManagerInterface::class);

        $todoListManager = new TodoListManager($repository, $eventManager);
        $todoListManager->findTodoListById('todo-list-id');
    }

    public function testFindTodoListByIdNotFound()
    {
        $repository = $this->createMock(RepositoryInterface::class);
        $repository->expects($this->once())->method('findOneById')
            ->with('todo-list-id')->willReturn(null);

        $eventManager = $this->createMock(EventManagerInterface::class);

        $todoListManager = new TodoListManager($repository, $eventManager);
        $this->expectException(TodoListNotFoundException::class);
        $todoListManager->findTodoListById('todo-list-id');
    }

    public function testDeleteTodoList()
    {
        $todoList = $this->createMock(TodoList::class);
        $todoList->method('getId')->willReturn('todo-list-id');

        $repository = $this->createMock(RepositoryInterface::class);
        $repository->expects($this->once())->method('deleteById')->with('todo-list-id');

        $eventManager = $this->createMock(EventManagerInterface::class);
        $eventManager->expects($this->once())->method('emitEvent')->with(new TodoListDeletedEvent($todoList));

        $todoListManager = new TodoListManager($repository, $eventManager);
        $todoListManager->deleteTodoList($todoList);
    }
}
