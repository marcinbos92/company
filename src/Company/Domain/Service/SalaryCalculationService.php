<?php
declare(strict_types=1);
namespace App\Company\Domain\Service;

use App\Company\Domain\BonusType;

class SalaryCalculationService
{
    /** @var SalaryCalculatorInterface[] */
    private array $registeredCalculatorsForBonusTypes;

    /** @param array<int, SalaryCalculatorInterface> $calculators */
    public function __construct(array $calculators)
    {
        $this->registeredCalculatorsForBonusTypes = $calculators;
    }

    public function calculate(float $baseSalary, BonusType $bonusType, float $bonusValue, int $seniority): float
    {
        if (!array_key_exists($bonusType->value, $this->registeredCalculatorsForBonusTypes)) {
            throw new \InvalidArgumentException('Provided bonus type must be registered in salary calculator.');
        }

        return $this->registeredCalculatorsForBonusTypes[$bonusType->value]->calculate($baseSalary, $bonusValue, $seniority);
    }
}
