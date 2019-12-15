<?php declare(strict_types=1);

namespace App\Application\UseCases\TodoListCreateItem;

use App\Application\UseCases\TodoListItemUpdate\TodoListItemUpdateCommand;
use App\Application\Validator\ValidationResult;
use App\Application\Validator\ValidatorInterface;

class TodoListCreateItemCommandValidator implements ValidatorInterface
{
    /**
     * @param TodoListCreateItemCommand $data
     *
     * @return ValidationResult
     */
    public function validate($data): ValidationResult
    {
        $validationResult = new ValidationResult();
        $validationResult->setValid(true);
        $this->validateTitle($data, $validationResult);

        return $validationResult;
    }

    /**
     * @param $data
     *
     * @return bool
     */
    public function canValidate($data): bool
    {
        return $data instanceof TodoListCreateItemCommand || $data instanceof TodoListItemUpdateCommand;
    }

    /**
     * @param TodoListCreateItemCommand|TodoListItemUpdateCommand $data
     * @param ValidationResult $validationResult
     */
    private function validateTitle($data, ValidationResult $validationResult): void
    {
        $name = $data->getTitle();
        if (empty($name)) {
            $validationResult->setValid(false);
            $validationResult->addError('Empty title', 'title');
        }
    }
}
