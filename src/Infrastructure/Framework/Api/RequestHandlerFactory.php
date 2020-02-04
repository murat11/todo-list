<?php declare(strict_types=1);

namespace App\Infrastructure\Framework\Api;

use App\Infrastructure\Framework\CommandBus\CommandBus;
use App\Infrastructure\Framework\CommandBus\CommandBusAwareInterface;
use App\Infrastructure\Framework\Serializer\SerializerAwareInterface;
use App\Infrastructure\Framework\Serializer\SerializerInterface;

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
