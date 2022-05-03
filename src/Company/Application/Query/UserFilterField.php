<?php
declare(strict_types=1);
namespace App\Company\Application\Query;

enum UserFilterField: string
{
    case NAME = 'name';
    case SURNAME = 'surname';
    case DEPARTMENT_NAME = 'departmentName';
}
