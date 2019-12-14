<?php declare(strict_types=1);

namespace App\Infrastructure\CommandBus;

/**
 * Class CommandBus
 */
class CommandBus
{
    /**
     * @var HandlerResolver
     */
    private $handlerResolver;

    /**
     * @param HandlerResolver $handlerResolver
     */
    public function __construct(HandlerResolver $handlerResolver)
    {
        $this->handlerResolver = $handlerResolver;
    }

    /**
     * @param $command
     *
     * @return mixed
     */
    public function handle($command)
    {
        $handler = $this->handlerResolver->getHandlerForCommand($command);

        return $handler->handle($command);
    }
}
