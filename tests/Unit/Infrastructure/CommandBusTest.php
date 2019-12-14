<?php

namespace Test\Unit\Infrastructure;

use App\Infrastructure\CommandBus\CommandBus;
use App\Infrastructure\CommandBus\HandlerResolver;
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

        (new CommandBus($handlerResolver))->handle($command);
    }
}

class TestCommandHandler
{
    public function handle($command)
    {
    }
}
