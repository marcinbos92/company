<?php
declare(strict_types=1);
namespace App\Company\Application\Query;

enum UserSortField: string
{
    case NAME = 'name';
    case SURNAME = 'surname';
    case DEPARTMENT_NAME = 'departmentName';
    case BASE_SALARY = 'baseSalary';
    case BONUS_SALARY = 'bonusSalary';
    case BONUS_TYPE = 'bonusType';
    case SALARY = 'salary';
}
