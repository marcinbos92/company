<?php
declare(strict_types=1);
namespace App\Company\Domain\Service;

class ConstantBonusSalaryCalculator implements SalaryCalculatorInterface
{
    private const MAXIMUM_SENIORITY = 10;

    public function calculate(float $baseSalary, float $bonusValue, int $seniority): float
    {
        return (float) match (true) {
            $seniority === 0 => 0,
            $seniority > self::MAXIMUM_SENIORITY => self::MAXIMUM_SENIORITY * $bonusValue,
            ($seniority > 0) && ($seniority <= self::MAXIMUM_SENIORITY) => $seniority * $bonusValue,
        };
    }
}
