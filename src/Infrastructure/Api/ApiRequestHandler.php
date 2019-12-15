<?php declare(strict_types=1);

namespace App\Infrastructure\Api;

use App\Application\Validator\ValidationException;
use App\Infrastructure\Api\Exceptions\BadRequestException;
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
     * @param $command
     *
     * @return mixed
     */
    protected function handleCommand($command)
    {
        try {
            $result = $this->commandBus->handle($command);
        } catch (ValidationException $x) {
            throw new BadRequestException($x->getValidationErrors(), $x);
        }

        return $this->serializer->serialize($result);
    }

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
