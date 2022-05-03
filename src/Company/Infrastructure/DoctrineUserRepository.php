<?php
declare(strict_types=1);
namespace App\Company\Infrastructure;

use App\Company\Domain\Entity\User;
use App\Company\Domain\UserRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineUserRepository implements UserRepositoryInterface
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function add(User $user): void
    {
        $this->em->persist($user);
    }

    public function save(): void
    {
        $this->em->flush();
    }
}
