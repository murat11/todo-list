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
    private $handlerFactory;

    /**
     * @param HandlerResolver $handlerFactory
     */
    public function __construct(HandlerResolver $handlerFactory)
    {
        $this->handlerFactory = $handlerFactory;
    }

    /**
     * @param $command
     *
     * @return mixed
     */
    public function handle($command)
    {
        $handler = $this->handlerFactory->getHandlerForCommand($command);

        return $handler->handle($command);
    }
}
