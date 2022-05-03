<?php
declare(strict_types=1);
namespace App\Company\Application\Query;

final class UserFilter
{
    public const DEFAULT_ORDER_BY = UserSortField::NAME;
    public const DEFAULT_ORDER = 'asc';
    public const ORDERS = [
        UserSortField::NAME,
        UserSortField::SURNAME,
        UserSortField::DEPARTMENT_NAME,
        UserSortField::BASE_SALARY,
        UserSortField::BONUS_SALARY,
        UserSortField::BONUS_TYPE,
        UserSortField::SALARY,
    ];

    public readonly UserSortField $orderBy;
    public readonly string $order;
    public readonly ?UserFilterField $filterBy;

    public readonly ?string $department;
    public readonly ?string $name;
    public readonly ?string $surname;

    private function __construct() {}

    /** @param mixed[] $data */
    public static function fromQuery(array $data): self
    {
        $self = new self();

        $self->orderBy = UserFilter::DEFAULT_ORDER_BY;
        if (array_key_exists('orderBy', $data)) {
            if (!in_array($data['orderBy'], self::ORDERS)) {
                throw new \InvalidArgumentException('Incorrect order by value.');
            }

            $self->orderBy = $data['orderBy'];
        }

        $self->order = UserFilter::DEFAULT_ORDER;
        if (array_key_exists('order', $data)) {
            if (!in_array($data['order'], ['asc', 'desc'])) {
                throw new \InvalidArgumentException('Incorrect order value.');
            }

            $self->order = $data['order'];
        }

        $self->department = array_key_exists('department', $data) ? (string) $data['departmentName'] : null;
        $self->name = array_key_exists('name', $data) ? $data['name'] : null;
        $self->surname = array_key_exists('surname', $data) ? $data['surname'] : null;

        return $self;
    }
}
