<?php
declare(strict_types=1);
namespace App\Company\Infrastructure;

use App\Company\Application\Query\UserFilter;
use App\Company\Application\Query\UserQueryInterface;
use App\Company\Application\Query\UserSortField;
use App\Company\Application\Query\UserView;
use App\Company\Domain\BonusType;
use App\Company\Domain\Service\ConstantBonusSalaryCalculator;
use App\Company\Domain\Service\PercentageBonusSalaryCalculator;
use App\Company\Domain\Service\SalaryCalculationService;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

class DbalUserQuery implements UserQueryInterface
{
    public function __construct(
        private Connection $connection,
    ) {}

    /** @return UserView[] */
    public function filter(UserFilter $filter): array
    {
        $data = $this
            ->buildQuery($filter)
            ->executeQuery()
            ->fetchAllAssociative();

        return $this->prepareViews($data);
    }

    private function buildQuery(UserFilter $filter): QueryBuilder
    {
        $query = $this
            ->connection
            ->createQueryBuilder()
            ->from('users', 'u')
            ->leftJoin('u', 'departments', 'd', 'u.department_id = d.id')
            ->select('u.name AS user_name, u.surname AS user_surname, u.base_salary' .
                ', d.name AS department_name, d.bonus_type, d.bonus_value' .
                ', (date_part(\'year\', age(now()::date, started_work_on::date))) AS seniority'
            );

        if ($filter->getOrderBy() === UserSortField::BONUS_SALARY || $filter->getOrderBy() === UserSortField::SALARY) {
            $query = $this->buildCustomQueryForSorting();
        }

        $orderMap = [
            UserSortField::NAME->value => 'user_name',
            UserSortField::SURNAME->value => 'user_surname',
            UserSortField::DEPARTMENT_NAME->value => 'department_name',
            UserSortField::BASE_SALARY->value => 'base_salary',
            UserSortField::BONUS_TYPE->value => 'bonus_type',
            UserSortField::BONUS_SALARY->value => 'bonus_salary',
            UserSortField::SALARY->value => 'total_salary',
        ];

        $filter->getDepartment() && $query
            ->andWhere('d.name = :departmentName')
            ->setParameter('departmentName', $filter->getDepartment());

        $filter->getName() && $query
            ->andWhere('u.name = :userName')
            ->setParameter('userName', $filter->getName());

        $filter->getSurname() && $query
            ->andWhere('u.surname = :userSurname')
            ->setParameter('userSurname', $filter->getSurname());

        $filter->getOrderBy() && $query->addOrderBy($orderMap[$filter->getOrderBy()->value], $filter->getOrder());

        return $query;
    }

    private function buildCustomQueryForSorting(): QueryBuilder
    {
        $innerFromSql = <<<SQL
            (SELECT
                u.name                                                       AS user_name,
                u.surname                                                    AS user_surname,
                u.department_id,
                u.base_salary,
                date_part('year', age(now()::date, u.started_work_on::date)) AS seniority FROM users u
            )
        SQL;

        $selectSql = <<<SQL
               user_name,
               user_surname,
               seniority,
               base_salary,
               bonus_value,
               bonus_type,
               d.name AS department_name,
               (
                   CASE
                       WHEN seniority > 1 AND seniority <= 10 AND bonus_type = 1 THEN bonus_value * seniority
                       WHEN seniority > 10 AND bonus_type = 1 THEN bonus_value * 10
                       WHEN bonus_type = 2 THEN (bonus_value::float /100) * base_salary
                   END
               ) AS bonus_salary,
               (
                   CASE
                       WHEN seniority > 1 AND seniority <= 10 AND bonus_type = 1 THEN bonus_value * seniority + base_salary
                       WHEN seniority > 10 AND bonus_type = 1 THEN bonus_value * 10 + base_salary
                       WHEN bonus_type = 2 THEN (bonus_value::float /100) * base_salary + base_salary
                       END
                   ) AS total_salary
        SQL;

        return $this
            ->connection
            ->createQueryBuilder()
            ->from($innerFromSql, 'u')
            ->select($selectSql)
            ->leftJoin('u', 'departments', 'd', 'u.department_id = d.id');
    }

    /**
     * @param mixed[] $data
     * @return UserView[]
     */
    private function prepareViews(array $data): array
    {
        $salaryCalculatorService = new SalaryCalculationService(
            [
                BonusType::CONSTANT->value => new ConstantBonusSalaryCalculator(),
                BonusType::PERCENTAGE->value => new PercentageBonusSalaryCalculator(),
            ]
        );

        return array_map(function (array $user) use ($salaryCalculatorService) {
            $baseSalary = $user['base_salary'];
            $bonusType = BonusType::from((int)$user['bonus_type']);
            $bonusValue = $user['bonus_value'];

            $bonus = $salaryCalculatorService->calculate($baseSalary, $bonusType, $bonusValue, (int) $user['seniority']);

            return new UserView(
                $user['user_name'],
                $user['user_surname'],
                $user['department_name'],
                $baseSalary,
                $bonus,
                $bonusType->name(),
                $baseSalary + $bonus
            );
        }, $data);
    }
}
