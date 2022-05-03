<?php
namespace App\Company\Domain\Service;
use App\Company\Domain\BonusType;

interface SalaryCalculatorInterface
{
    // Future improvement - Value object for these parameters
    public function calculate(float $baseSalary, float $bonusValue, int $seniority): float;
}
