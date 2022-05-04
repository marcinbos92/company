<?php
declare(strict_types=1);
namespace App\Company\Infrastructure\Controller;

use App\Company\Application\Query\ListView;
use App\Company\Application\Query\UserFilter;
use App\Company\Application\Query\UserQueryInterface;
use App\Company\Domain\DepartmentRepositoryInterface;
use App\Company\Domain\Entity\User;
use App\Company\Domain\UserRepositoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController
{
    public function __construct(
        private readonly UserQueryInterface $userQuery,
        private readonly DepartmentRepositoryInterface $departments,
        private readonly UserRepositoryInterface $users
    )
    {}

    #[Route('/api/users', name: 'users_list', methods: ['GET'])]
    public function listAction(Request $request): JsonResponse
    {
        // Future improvement -> inject symfony validator and check request data - basic types etc.

        $query = [];
        $request->query->has('orderBy') && $query['orderBy'] = $request->query->get('orderBy');
        $request->query->has('order') && $query['order'] = $request->query->get('order');
        $request->query->has('department') && $query['department'] = $request->query->get('department');
        $request->query->has('name') && $query['name'] = $request->query->get('name');
        $request->query->has('surname') && $query['surname'] = $request->query->get('surname');

        try {
            $userData = $this->userQuery->filter(UserFilter::fromQuery($query));
        } catch (\InvalidArgumentException $exception) {
            return new JsonResponse($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse(ListView::fromData($userData));
    }

    #[Route('/api/users', name: 'users_create', methods: ['POST'])]
    public function createAction(Request $request): JsonResponse
    {
        // Future improvement -> handle request data from POST and validate using for example symfony validator

        $hrDepartment = $this->departments->findUsingName('Human Resources');
        $csDepartment = $this->departments->findUsingName('Customer Service');

        $hrEmployee = new User('Adam', 'Kowalski', 1000, new \DateTimeImmutable('2007-05-01'), $hrDepartment);
        $csEmployee = new User('Anna', 'Nowak', 1100, new \DateTimeImmutable('2017-05-01'), $csDepartment);

        $this->users->add($hrEmployee);
        $this->users->add($csEmployee);
        $this->users->save();

        return new JsonResponse('Created.', Response::HTTP_CREATED);
    }
}
