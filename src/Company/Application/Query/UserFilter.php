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

    private UserSortField $orderBy;
    private string $order;

    private ?string $department;
    private ?string $name;
    private ?string $surname;

    private function __construct()
    {
        $this->orderBy = UserFilter::DEFAULT_ORDER_BY;
        $this->order = UserFilter::DEFAULT_ORDER;
    }

    /** @param mixed[] $data */
    public static function fromQuery(array $data): self
    {
        $availableOrders = fn () => array_map(fn (UserSortField $field) => $field->value, self::ORDERS);

        $self = new self();

        if (array_key_exists('orderBy', $data)) {
            if (!in_array($data['orderBy'], $availableOrders())) {
                throw new \InvalidArgumentException('Incorrect order by value.');
            }

            $self->orderBy = UserSortField::from($data['orderBy']);
        }

        if (array_key_exists('order', $data)) {
            if (!in_array($data['order'], ['asc', 'desc'])) {
                throw new \InvalidArgumentException('Incorrect order value.');
            }

            $self->order = $data['order'];
        }

        $self->department = array_key_exists('department', $data) ? (string) $data['department'] : null;
        $self->name = array_key_exists('name', $data) ? $data['name'] : null;
        $self->surname = array_key_exists('surname', $data) ? $data['surname'] : null;

        return $self;
    }

    public function getOrderBy(): UserSortField
    {
        return $this->orderBy;
    }

    public function getOrder(): string
    {
        return $this->order;
    }

    public function getDepartment(): ?string
    {
        return $this->department;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }
}
