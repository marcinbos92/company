<?php
declare(strict_types=1);
namespace App\Company\Domain\Service;

class PercentageBonusSalaryCalculator implements SalaryCalculatorInterface
{
    public function calculate(float $baseSalary, float $bonusValue, int $seniority): float
    {
        return (float) match (true) {
            $seniority === 0 => 0,
            $seniority > 0 => ($bonusValue / 100) * $baseSalary,
        };
    }
}
