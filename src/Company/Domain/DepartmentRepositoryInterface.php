<?php
namespace App\Company\Domain;

use App\Company\Domain\Entity\Department;

interface DepartmentRepositoryInterface
{
    public function findUsingName(string $name): ?Department;

    public function add(Department $department): void;

    public function save(): void;
}
