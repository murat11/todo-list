<?php

namespace Test\Unit\Application\Validator;

use App\Application\Validator\ValidationResult;
use App\Application\Validator\ValidatorInterface;
use App\Application\Validator\ChainedValidator;
use PHPUnit\Framework\TestCase;

class ChainedValidatorTest extends TestCase
{
    public function testItCanValidateIfOneCan()
    {
        $validator1 = $this->createMock(ValidatorInterface::class);
        $validator1->expects($this->once())->method('canValidate')->with('some-data')->willReturn(false);

        $validator2 = $this->createMock(ValidatorInterface::class);
        $validator2->expects($this->once())->method('canValidate')->with('some-data')->willReturn(true);

        $this->assertTrue((new ChainedValidator([$validator1, $validator2]))->canValidate('some-data'));
    }

    public function testItCanNotValidateIfNoneCan()
    {
        $validator1 = $this->createMock(ValidatorInterface::class);
        $validator1->expects($this->once())->method('canValidate')->with('some-data')->willReturn(false);

        $validator2 = $this->createMock(ValidatorInterface::class);
        $validator2->expects($this->once())->method('canValidate')->with('some-data')->willReturn(false);

        $this->assertFalse((new ChainedValidator([$validator1, $validator2]))->canValidate('some-data'));
    }

    public function testItValidatedIfAllDid()
    {
        $validationResult = $this->createMock(ValidationResult::class);
        $validationResult->method('isValid')->willReturn(true);

        $validator1 = $this->createMock(ValidatorInterface::class);
        $validator1->method('canValidate')->with('some-data')->willReturn(true);
        $validator1->expects($this->once())->method('validate')
            ->with('some-data')
            ->willReturn($validationResult);

        $validator2 = $this->createMock(ValidatorInterface::class);
        $validator2->method('canValidate')->with('some-data')->willReturn(true);
        $validator2->expects($this->once())->method('validate')
            ->with('some-data')
            ->willReturn($validationResult);

        $this->assertTrue((new ChainedValidator([$validator1, $validator2]))->validate('some-data')->isValid());
    }

    public function testItFailedValidationIfOneFailed()
    {
        $validationResult1 = $this->createMock(ValidationResult::class);
        $validationResult1->method('isValid')->willReturn(true);

        $validationResult2 = $this->createMock(ValidationResult::class);
        $validationResult2->method('isValid')->willReturn(false);
        $validationResult2->method('getErrors')->willReturn([]);

        $validator1 = $this->createMock(ValidatorInterface::class);
        $validator1->method('canValidate')->with('some-data')->willReturn(true);
        $validator1->expects($this->once())->method('validate')
            ->with('some-data')
            ->willReturn($validationResult1);

        $validator2 = $this->createMock(ValidatorInterface::class);
        $validator2->method('canValidate')->with('some-data')->willReturn(true);
        $validator2->expects($this->once())->method('validate')
            ->with('some-data')
            ->willReturn($validationResult2);

        $this->assertFalse((new ChainedValidator([$validator1, $validator2]))->validate('some-data')->isValid());
    }


    public function testItFailedValidationAndErrorsMerged()
    {
        $validationResult1 = $this->createMock(ValidationResult::class);
        $validationResult1->method('isValid')->willReturn(false);
        $validationResult1->method('getErrors')->willReturn(['aaa' => ['ccc'], 'ddd' => ['eee']]);

        $validationResult2 = $this->createMock(ValidationResult::class);
        $validationResult2->method('isValid')->willReturn(false);
        $validationResult2->method('getErrors')->willReturn(['aaa' => ['bbb']]);

        $validator1 = $this->createMock(ValidatorInterface::class);
        $validator1->method('canValidate')->with('some-data')->willReturn(true);
        $validator1->expects($this->once())->method('validate')
            ->with('some-data')
            ->willReturn($validationResult1);

        $validator2 = $this->createMock(ValidatorInterface::class);
        $validator2->method('canValidate')->with('some-data')->willReturn(true);
        $validator2->expects($this->once())->method('validate')
            ->with('some-data')
            ->willReturn($validationResult2);

        $validationResult = (new ChainedValidator([$validator1, $validator2]))->validate('some-data');
        $this->assertFalse($validationResult->isValid());
        $this->assertEquals(
            ['aaa' => ['ccc', 'bbb'], 'ddd' => ['eee']],
            $validationResult->getErrors()
        );
    }
}

