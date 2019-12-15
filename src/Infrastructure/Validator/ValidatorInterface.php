<?php declare(strict_types=1);

namespace App\Infrastructure\Validator;

interface ValidatorInterface
{
    /**
     * @param $data
     *
     * @return ValidationResult
     */
    public function validate($data): ValidationResult;

    /**
     * @param $data
     *
     * @return bool
     */
    public function canValidate($data): bool;
}
