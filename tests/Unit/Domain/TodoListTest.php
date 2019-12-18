<?php

namespace Test\Unit\Domain;

use App\Domain\TodoList\Exception\TodoListItemNotFoundException;
use App\Domain\TodoList\TodoList;
use App\Domain\TodoList\TodoListItem;
use PHPUnit\Framework\TestCase;

class TodoListTest extends TestCase
{
    public function testDeleteItemById()
    {
        $item = $this->createMock(TodoListItem::class);
        $item->method('getId')->willReturn('list-item-id');

        $todoList = new TodoList();
        $todoList->addItem($item);
        $this->assertNotEmpty($todoList->getItems());
        $todoList->deleteItemById('list-item-id');
        $this->assertEmpty($todoList->getItems());
    }

    public function testDeleteItemByIdNotFound()
    {
        $item = $this->createMock(TodoListItem::class);
        $item->method('getId')->willReturn('list-item-id');

        $todoList = new TodoList();
        $todoList->addItem($item);
        $this->assertNotEmpty($todoList->getItems());

        $this->expectException(TodoListItemNotFoundException::class);
        $todoList->deleteItemById('other-list-item-id');
    }

    public function testGetItemById()
    {
        $itemToPut = $this->createMock(TodoListItem::class);
        $itemToPut->method('getId')->willReturn('list-item-id');

        $todoList = new TodoList();
        $todoList->addItem($itemToPut);
        $foundItem = $todoList->getItemById('list-item-id');
        $this->assertEquals($itemToPut, $foundItem);
    }

    public function testGetItemByIdNotFound()
    {
        $item = $this->createMock(TodoListItem::class);
        $item->method('getId')->willReturn('list-item-id');

        $todoList = new TodoList();
        $todoList->addItem($item);
        $this->assertNotEmpty($todoList->getItems());

        $this->expectException(TodoListItemNotFoundException::class);
        $todoList->getItemById('other-list-item-id');
    }

    public function testApplyNewStatusToAllItems()
    {
        $item1 = $this->createMock(TodoListItem::class);
        $item1->expects($this->once())->method('setCompleted')->with(true);

        $item2 = $this->createMock(TodoListItem::class);
        $item2->expects($this->once())->method('setCompleted')->with(true);

        $todoList = new TodoList();
        $todoList->addItem($item1);
        $todoList->addItem($item2);
        $todoList->applyNewStatusToAllItems(true);
    }

    public function testDeleteCompletedItems()
    {
        $item1 = $this->createMock(TodoListItem::class);
        $item1->method('isCompleted')->willReturn(true);

        $item2 = $this->createMock(TodoListItem::class);
        $item2->method('isCompleted')->willReturn(false);

        $item3 = $this->createMock(TodoListItem::class);
        $item3->method('isCompleted')->willReturn(true);

        $todoList = new TodoList();
        $todoList->addItem($item1);
        $todoList->addItem($item2);
        $todoList->addItem($item3);
        $this->assertCount(3, $todoList->getItems());
        $todoList->deleteCompletedItems();
        $this->assertCount(1, $todoList->getItems());
        $this->assertEquals($item2, array_shift($todoList->getItems()));

    }
}
