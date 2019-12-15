<?php

namespace Test\Unit\Application\UseCases;

use App\Application\UseCases\TodoListCreateCommand;
use App\Application\UseCases\TodoListCreateCommandValidator;
use PHPUnit\Framework\TestCase;

class TodoListCreateItemCommandValidatorTest extends TestCase
{
    public function testItCanValidate()
    {
        $command = $this->createMock(TodoListCreateCommand::class);
        $this->assertTrue((new TodoListCreateCommandValidator())->canValidate($command));
    }

    public function testItCanNotValidate()
    {
        $this->assertFalse((new TodoListCreateCommandValidator())->canValidate('another-thing'));
    }

    public function testValidationOk()
    {
        $command = $this->createMock(TodoListCreateCommand::class);
        $command->method('getName')->willReturn('The name');
        $command->method('getParticipantEmails')->willReturn(
            ['aaa@aa.com', 'bbb@bb.com']
        );

        $validator = new TodoListCreateCommandValidator();
        $result = $validator->validate($command);

        $this->assertTrue($result->isValid());
        $this->assertEmpty($result->getErrors());
    }

    public function testValidationFailedEmptyName()
    {
        $command = $this->createMock(TodoListCreateCommand::class);
        $command->method('getName')->willReturn('');
        $command->method('getParticipantEmails')->willReturn([]);

        $validator = new TodoListCreateCommandValidator();
        $result = $validator->validate($command);

        $this->assertFalse($result->isValid());
        $this->assertArrayHasKey('name', $result->getErrors());
    }

    public function testValidationFailedInvalidEmails()
    {
        $command = $this->createMock(TodoListCreateCommand::class);
        $command->method('getName')->willReturn('The name');
        $command->method('getParticipantEmails')->willReturn(['aaa', 'bbb', 'aaa@bbb.com']);

        $validator = new TodoListCreateCommandValidator();
        $result = $validator->validate($command);
        $errors = $result->getErrors();

        $this->assertFalse($result->isValid());
        $this->assertArrayHasKey('participants', $errors);
        $this->assertCount(2, $errors['participants']);
    }
}
