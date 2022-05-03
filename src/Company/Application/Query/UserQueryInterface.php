<?php
declare(strict_types=1);
namespace App\Company\Application\Query;

interface UserQueryInterface
{
    /** @return UserView[] */
    public function filter(UserFilter $filter): array;
}
