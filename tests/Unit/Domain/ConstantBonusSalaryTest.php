<?php
declare(strict_types=1);
namespace App\Tests\Unit\Domain;

use App\Company\Domain\Service\ConstantBonusSalaryCalculator;
use App\Company\Domain\Service\SalaryCalculatorInterface;
use PHPUnit\Framework\TestCase;

class ConstantBonusSalaryTest extends TestCase
{
    private SalaryCalculatorInterface $calculator;

    protected function setUp(): void
    {
        $this->calculator = new ConstantBonusSalaryCalculator();
    }

    public function testBonusSalaryCalculationForTheNewEmployee(): void
    {
        $value = $this->calculator->calculate(1000.0, 100.0, 0);

        $this->assertSame(0.0, $value);
    }

    public function testBonusSalaryCalculationForTheEmployeeWithSeniorityUnder10Years(): void
    {
        $value = $this->calculator->calculate(1000.0, 100.0, 9);

        $this->assertSame(900.0, $value);
    }

    public function testBonusSalaryCalculationForTheEmployeeWithSeniority10Years(): void
    {
        $value = $this->calculator->calculate(1000.0, 100.0, 10);

        $this->assertSame(1000.0, $value);
    }

    public function testBonusSalaryCalculationForTheEmployeeWithSeniorityMoreThen10Years(): void
    {
        $value = $this->calculator->calculate(1000.0, 100.0, 11);

        $this->assertSame(1000.0, $value);
    }
}
