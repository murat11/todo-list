<?php

namespace Test\Unit\Infrastructure;

use App\Application\Validator\ValidationResult;
use App\Application\Validator\ValidatorInterface;
use App\Infrastructure\Framework\CommandBus\CommandBus;
use App\Infrastructure\Framework\CommandBus\HandlerResolver;
use PHPUnit\Framework\TestCase;


class CommandBusTest extends TestCase
{
    public function testHandledOk()
    {
        $command = 'some-command';

        $testCommandHandler = $this->createMock(TestCommandHandler::class);
        $testCommandHandler->expects($this->once())->method('handle')->with($command);

        $handlerResolver = $this->createMock(HandlerResolver::class);
        $handlerResolver->expects($this->once())->method('getHandlerForCommand')->with($command)->willReturn($testCommandHandler);

        $validationResult = $this->createMock(ValidationResult::class);
        $validationResult->method('isValid')->willReturn(true);

        $validator = $this->createMock(ValidatorInterface::class);
        $validator->expects($this->once())->method('validate')->with($command)->willReturn($validationResult);

        (new CommandBus($handlerResolver, $validator))->handle($command);
    }
}

class TestCommandHandler
{
    public function handle($command)
    {
    }
}
