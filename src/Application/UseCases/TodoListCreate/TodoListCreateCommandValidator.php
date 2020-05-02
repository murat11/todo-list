<?php declare(strict_types=1);

namespace App\Application\UseCases\TodoListCreate;

use App\Application\UseCases\TodoList\CreateListCommand;
use App\Application\Validator\ValidationResult;
use App\Application\Validator\ValidatorInterface;

class TodoListCreateCommandValidator implements ValidatorInterface
{
    /**
     * @param CreateListCommand $data
     *
     * @return ValidationResult
     */
    public function validate($data): ValidationResult
    {
        $validationResult = new ValidationResult();
        $validationResult->setValid(true);

        $this->validateName($data, $validationResult);
        $this->validateParticipantEmails($data, $validationResult);

        return $validationResult;
    }

    /**
     * @param $data
     *
     * @return bool
     */
    public function canValidate($data): bool
    {
        return $data instanceof CreateListCommand;
    }

    /**
     * @param CreateListCommand $data
     * @param ValidationResult $validationResult
     */
    private function validateName(CreateListCommand $data, ValidationResult $validationResult): void
    {
        $name = $data->getName();
        if (empty($name)) {
            $validationResult->setValid(false);
            $validationResult->addError('Empty name', 'name');
        }
    }

    /**
     * @param CreateListCommand $data
     * @param ValidationResult $validationResult
     */
    private function validateParticipantEmails(CreateListCommand $data, ValidationResult $validationResult): void
    {
        $emails = $data->getParticipantEmails();
        if (empty($emails)) {
            return;
        }

        foreach ($emails as $email) {
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                continue;
            }

            $validationResult->setValid(false);
            $validationResult->addError(sprintf('Email %s is not valid', $email), 'participants');
        }
    }
}
