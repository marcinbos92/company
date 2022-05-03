<?php
declare(strict_types=1);
namespace App\Tests\Unit\Domain;

use App\Company\Domain\Service\ConstantBonusSalaryCalculator;
use App\Company\Domain\Service\PercentageBonusSalaryCalculator;
use App\Company\Domain\Service\SalaryCalculatorInterface;
use PHPUnit\Framework\TestCase;

class PercentageBonusSalaryTest extends TestCase
{
    private SalaryCalculatorInterface $calculator;

    protected function setUp(): void
    {
        $this->calculator = new PercentageBonusSalaryCalculator();
    }

    public function testBonusSalaryCalculationForTheNewEmployee(): void
    {
        $value = $this->calculator->calculate(1000.0, 10.0, 0);

        $this->assertSame(0.0, $value);
    }

    public function testBonusSalaryCalculationForEmployee(): void
    {
        $value = $this->calculator->calculate(1000.0, 10.0, 1);

        $this->assertSame(100.0, $value);
    }
}
