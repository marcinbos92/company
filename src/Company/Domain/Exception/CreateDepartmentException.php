<?php
declare(strict_types=1);
namespace App\Company\Domain\Exception;

class CreateDepartmentException extends \RuntimeException
{
    public static function fromIncorrectPercentageValue(): self
    {
        return new static('Department percentage value must be between 1 and 100.');
    }
}
