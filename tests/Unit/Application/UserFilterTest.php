<?php
declare(strict_types=1);
namespace App\Tests\Unit\Application;

use App\Company\Application\Query\UserFilter;
use App\Company\Application\Query\UserSortField;
use PHPUnit\Framework\TestCase;

class UserFilterTest extends TestCase
{
    /** @dataProvider provideQueryData */
    public function testDefaultObjectCreation(array $query, array $expectedData): void
    {
        $filter = UserFilter::fromQuery($query);

        $this->assertEquals($filter->getOrderBy()->value, $expectedData['orderBy']);
        $this->assertEquals($filter->getOrder(), $expectedData['order']);
        $this->assertEquals($filter->getDepartment(), $expectedData['department']);
        $this->assertEquals($filter->getName(), $expectedData['name']);
        $this->assertEquals($filter->getSurname(), $expectedData['surname']);
    }

    public function testIncorrectOrderByParameter(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $filter = UserFilter::fromQuery(['orderBy' => 'abc']);
    }

    public function testIncorrectOrderParameter(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $filter = UserFilter::fromQuery(['order' => 'abc']);
    }

    /** @return mixed[] */
    private function provideQueryData(): array
    {
        return [
            [ // Empty query
                [],
                ['orderBy' => UserSortField::NAME->value, 'order' => 'asc', 'department' => null, 'name' => null, 'surname' => null]
            ],
            [
                ['orderBy' => UserSortField::SURNAME->value],
                ['orderBy' => UserSortField::SURNAME->value, 'order' => 'asc', 'department' => null, 'name' => null, 'surname' => null]
            ],
            [
                ['orderBy' => UserSortField::DEPARTMENT_NAME->value],
                ['orderBy' => UserSortField::DEPARTMENT_NAME->value, 'order' => 'asc', 'department' => null, 'name' => null, 'surname' => null]
            ],
            [
                ['orderBy' => UserSortField::BASE_SALARY->value],
                ['orderBy' => UserSortField::BASE_SALARY->value, 'order' => 'asc', 'department' => null, 'name' => null, 'surname' => null]
            ],
            [
                ['orderBy' => UserSortField::BONUS_SALARY->value],
                ['orderBy' => UserSortField::BONUS_SALARY->value, 'order' => 'asc', 'department' => null, 'name' => null, 'surname' => null]
            ],
            [
                ['orderBy' => UserSortField::BONUS_TYPE->value],
                ['orderBy' => UserSortField::BONUS_TYPE->value, 'order' => 'asc', 'department' => null, 'name' => null, 'surname' => null]
            ],
            [
                ['orderBy' => UserSortField::SALARY->value],
                ['orderBy' => UserSortField::SALARY->value, 'order' => 'asc', 'department' => null, 'name' => null, 'surname' => null]
            ],
            [
                ['orderBy' => UserSortField::SURNAME->value, 'order' => 'asc'],
                ['orderBy' => UserSortField::SURNAME->value, 'order' => 'asc', 'department' => null, 'name' => null, 'surname' => null]
            ],
            [
                ['orderBy' => UserSortField::SURNAME->value, 'order' => 'desc'],
                ['orderBy' => UserSortField::SURNAME->value, 'order' => 'desc', 'department' => null, 'name' => null, 'surname' => null]
            ],
            [
                ['department' => 'abc'],
                ['orderBy' => UserSortField::NAME->value, 'order' => 'asc', 'department' => 'abc', 'name' => null, 'surname' => null]
            ],
            [
                ['name' => 'abc'],
                ['orderBy' => UserSortField::NAME->value, 'order' => 'asc', 'department' => null, 'name' => 'abc', 'surname' => null]
            ],
            [
                ['surname' => 'abc'],
                ['orderBy' => UserSortField::NAME->value, 'order' => 'asc', 'department' => null, 'name' => null, 'surname' => 'abc']
            ],
        ];
    }
}
