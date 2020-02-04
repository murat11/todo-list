<?php declare(strict_types=1);

namespace App\Infrastructure\Framework\Validator;

use App\Application\Validator\ValidationResult;
use App\Application\Validator\ValidatorInterface;

class ChainedValidator implements ValidatorInterface
{
    /**
     * @var ValidatorInterface[]
     */
    private $validators;

    /**
     * @param array $validators
     */
    public function __construct(array $validators)
    {
        $this->validators = $validators;
    }

    /**
     * @inheritDoc
     */
    public function validate($data): ValidationResult
    {
        /** @var ValidatorInterface[] $applicableValidators */
        $applicableValidators = array_filter(
            $this->validators,
            function (ValidatorInterface $validator) use ($data) {
                return $validator->canValidate($data);
            }
        );

        $jointResult = new ValidationResult();
        $jointResult->setValid(true);

        foreach ($applicableValidators as $validator) {
            $result = $validator->validate($data);
            if ($result->isValid()) {
                continue;
            }
            $jointResult->setValid(false);
            $jointResult->adoptErrorsFromOtherResult($result);
        }

        return $jointResult;
    }

    /**
     * @inheritDoc
     */
    public function canValidate($data): bool
    {
        foreach ($this->validators as $validator) {
            if ($validator->canValidate($data)) {
                return true;
            }
        }

        return false;
    }
}
