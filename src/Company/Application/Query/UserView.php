<?php
declare(strict_types=1);
namespace App\Company\Application\Query;

final class UserView extends AbstractView
{
    public function __construct(
        public readonly string $name,
        public readonly string $surname,
        public readonly string $departmentName,
        public readonly float $baseSalary,
        public readonly float $bonusSalary,
        public readonly string $bonusType,
        public readonly float $salary,
    ) {}
}
