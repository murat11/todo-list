<?php declare(strict_types=1);

namespace App\Application\Validator;

use InvalidArgumentException;

class ValidationException extends InvalidArgumentException
{
    /**
     * @var array
     */
    private $validationErrors;

    /**
     * @param ValidationResult $result
     *
     * @return ValidationException
     */
    public static function fromValidatorResult(ValidationResult $result): self
    {
        return new self($result->getErrors());
    }

    /**
     * @param array $validationErrors
     */
    public function __construct(array $validationErrors)
    {
        parent::__construct("Command validation failed", 400);
        $this->validationErrors = $validationErrors;
    }

    /**
     * @return array
     */
    public function getValidationErrors(): array
    {
        return $this->validationErrors;
    }
}
