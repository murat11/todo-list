<?php declare(strict_types=1);

namespace App\Infrastructure\Validator;

class ValidationResult
{
    /**
     * @var bool
     */
    private $valid;

    /**
     * @var array
     */
    private $errors = [];

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return (bool) $this->valid;
    }

    /**
     * @param bool $valid
     */
    public function setValid(bool $valid): void
    {
        $this->valid = $valid;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @param string $error
     * @param string $field
     *
     * @return ValidationResult
     */
    public function addError(string $error, string $field = null): self
    {
        $this->errors[$field ?? '_'][] = $error;

        return $this;
    }

    /**
     * @param ValidationResult $otherResult
     *
     * @return ValidationResult
     */
    public function adoptErrorsFromOtherResult(ValidationResult $otherResult): self
    {
        foreach ($otherResult->getErrors() as $field => $errors) {
            foreach ($errors as $error) {
                $this->addError($error, $field);
            }
        }

        return $this;
    }
}
