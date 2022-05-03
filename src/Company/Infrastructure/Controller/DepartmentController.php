<?php
declare(strict_types=1);
namespace App\Company\Infrastructure\Controller;

use App\Company\Domain\DepartmentRepositoryInterface;
use App\Company\Domain\Entity\Department;
use Faker\Factory;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DepartmentController
{
    public function __construct(
        private readonly DepartmentRepositoryInterface $departments
    ) {}

    #[Route('/api/departments', name: 'departments_create', methods: ['POST'])]
    public function createAction(Request $request): JsonResponse
    {
        $hrDepartment = Department::createWithConstantBonus('Human Resources', 100);
        $csDepartment = Department::createWithPercentageBonus('Customer Service', 10);

        $this->departments->add($hrDepartment);
        $this->departments->add($csDepartment);

        $this->departments->save();

        return new JsonResponse('Created.', Response::HTTP_CREATED);
    }
}
