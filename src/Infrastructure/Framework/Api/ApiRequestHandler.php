<?php declare(strict_types=1);

namespace App\Infrastructure\Framework\Api;

use App\Application\Validator\ValidationException;
use App\Domain\TodoList\Exception\TodoListItemNotFoundException;
use App\Domain\TodoList\Exception\TodoListNotFoundException;
use App\Infrastructure\Framework\Api\Exceptions\BadRequestException;
use App\Infrastructure\Framework\Api\Exceptions\NotFoundException;
use App\Infrastructure\Framework\CommandBus\CommandBus;
use App\Infrastructure\Framework\CommandBus\CommandBusAwareInterface;
use App\Infrastructure\Framework\Serializer\SerializerAwareInterface;
use App\Infrastructure\Framework\Serializer\SerializerInterface;

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
        } catch (TodoListNotFoundException|TodoListItemNotFoundException $x) {
            throw new NotFoundException($x->getMessage());
        }

        if (!empty($result)) {
            $result = $this->serializer->serialize($result);
        }

        return $result;
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
