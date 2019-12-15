<?php

namespace Test\Unit\Infrastructure\Validator;

use App\Infrastructure\Validator\ValidationResult;
use PHPUnit\Framework\TestCase;

class ValidationResultTest extends TestCase
{
    public function testAdoptOk()
    {
        $validationResult = new ValidationResult();
        $validationResult->addError('Error01');
        $validationResult->addError('Error11', 'field1');
        $validationResult->addError('Error12', 'field1');
        $validationResult->addError('Error21', 'field2');

        $otherResult = new ValidationResult();
        $otherResult->addError('Error02');
        $otherResult->addError('Error31', 'field3');
        $otherResult->addError('Error13', 'field1');
        $otherResult->addError('Error22', 'field2');

        $validationResult->adoptErrorsFromOtherResult($otherResult);
        $this->assertEquals(
            [
                '_' => ['Error01', 'Error02'],
                'field1' => ['Error11', 'Error12', 'Error13'],
                'field2' => ['Error21', 'Error22'],
                'field3' => ['Error31'],
            ],
            $validationResult->getErrors()
        );
    }
}
