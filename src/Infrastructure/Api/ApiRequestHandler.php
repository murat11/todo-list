<?php declare(strict_types=1);

namespace App\Infrastructure\Api;

use App\Infrastructure\CommandBus\CommandBus;
use App\Infrastructure\CommandBus\CommandBusAwareInterface;
use App\Infrastructure\Serializer\SerializerAwareInterface;
use App\Infrastructure\Serializer\SerializerInterface;

abstract class ApiRequestHandler implements CommandBusAwareInterface, SerializerAwareInterface
{
    /**
     * @var CommandBus
     */
    protected $commandBus;

    /**
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * @param ApiRequest $request
     *
     * @return ApiResponse
     */
    abstract function handle(ApiRequest $request): ApiResponse;

    /**
     * @param CommandBus $commandBus
     */
    public function setCommandBus(CommandBus $commandBus): void
    {
        $this->commandBus = $commandBus;
    }

    /**
     * @param SerializerInterface $serializer
     */
    public function setSerializer(SerializerInterface $serializer): void
    {
        $this->serializer = $serializer;
    }
}
