<?php declare(strict_types=1);

namespace App\Infrastructure\Api;

use App\Infrastructure\CommandBus\CommandBus;
use App\Infrastructure\CommandBus\CommandBusAwareInterface;
use App\Infrastructure\Serializer\SerializerAwareInterface;
use App\Infrastructure\Serializer\SerializerInterface;

class RequestHandlerFactory
{
    /**
     * @var CommandBus
     */
    private $commandBus;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @param CommandBus $commandBus
     * @param SerializerInterface $serializer
     */
    public function __construct(CommandBus $commandBus, SerializerInterface $serializer)
    {
        $this->commandBus = $commandBus;
        $this->serializer = $serializer;
    }

    /**
     * @param string $class
     *
     * @return ApiRequestHandler
     */
    public function createRequestHandler(string $class): ApiRequestHandler
    {
        /** @var ApiRequestHandler $requestHandler */
        $requestHandler = new $class();
        if ($requestHandler instanceof CommandBusAwareInterface) {
            $requestHandler->setCommandBus($this->commandBus);
        }
        if ($requestHandler instanceof SerializerAwareInterface) {
            $requestHandler->setSerializer($this->serializer);
        }

        return $requestHandler;
    }
}
