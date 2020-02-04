<?php declare(strict_types=1);

namespace App\Infrastructure\Framework\CommandBus;

use App\Application\Validator\ValidationException;
use App\Application\Validator\ValidatorInterface;

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
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @param HandlerResolver $handlerResolver
     * @param ValidatorInterface $validator
     */
    public function __construct(HandlerResolver $handlerResolver, ValidatorInterface $validator)
    {
        $this->handlerResolver = $handlerResolver;
        $this->validator = $validator;
    }

    /**
     * @param $command
     *
     * @return mixed
     */
    public function handle($command)
    {
        $this->validateCommand($command);
        $handler = $this->handlerResolver->getHandlerForCommand($command);

        return $handler->handle($command);
    }

    private function validateCommand($command): void
    {
        $validationResult = $this->validator->validate($command);
        if (!$validationResult->isValid()) {
            throw ValidationException::fromValidatorResult($validationResult);
        }
    }
}
