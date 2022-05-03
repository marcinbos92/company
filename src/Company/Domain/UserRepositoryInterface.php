<?php
namespace App\Company\Domain;

use App\Company\Domain\Entity\User;

interface UserRepositoryInterface
{
    public function add(User $user): void;

    public function save(): void;
}
