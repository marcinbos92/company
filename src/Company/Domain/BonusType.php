<?php
declare(strict_types=1);
namespace App\Company\Domain;

enum BonusType: int
{
    case CONSTANT = 1;
    case PERCENTAGE = 2;

    public function name(): string
    {
        return match ($this)
        {
            BonusType::CONSTANT => 'constant (value)',
            BonusType::PERCENTAGE => 'percentage (%)',
        };
    }
}
