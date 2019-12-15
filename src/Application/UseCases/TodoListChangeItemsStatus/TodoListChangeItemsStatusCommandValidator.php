<?php declare(strict_types=1);

namespace App\Application\UseCases\TodoListChangeItemsStatus;

use App\Application\Validator\ValidationResult;
use App\Application\Validator\ValidatorInterface;

class TodoListChangeItemsStatusCommandValidator implements ValidatorInterface
{
    /**
     * @param TodoListChangeItemsStatusCommand $data
     *
     * @return ValidationResult
     */
    public function validate($data): ValidationResult
    {
        $validationResult = new ValidationResult();
        $validationResult->setValid(true);

        if ($data->isCompleted() === null) {
            $validationResult->setValid(false);
            $validationResult->addError('argument is required', 'completed');
        }

        return $validationResult;
    }

    /**
     * @param $data
     *
     * @return bool
     */
    public function canValidate($data): bool
    {
        return $data instanceof TodoListChangeItemsStatusCommand;
    }
}
