<?php
declare(strict_types=1);
namespace App\Company\Infrastructure;

use App\Company\Domain\DepartmentRepositoryInterface;
use App\Company\Domain\Entity\Department;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineDepartmentRepository implements DepartmentRepositoryInterface
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function findUsingName(string $name): ?Department
    {
        $items = $this->em->getUnitOfWork()->getEntityPersister(Department::class)->loadAll(['name' => $name]);

        return false === empty($items) ? $items[0] : null;
    }

    public function add(Department $department): void
    {
        $this->em->persist($department);
    }

    public function save(): void
    {
        $this->em->flush();
    }
}
