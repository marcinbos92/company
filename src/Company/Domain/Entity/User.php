<?php
declare(strict_types=1);
namespace App\Company\Domain\Entity;

use App\Company\Infrastructure\DoctrineUserRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;

/**
 * @Entity
 * @Table(name="users")
 * @ORM\Entity(repositoryClass=DoctrineUserRepository::class)
 * Future improvement - moved mappings to XML to avoid Infrastructure namespace in Domain
 * Maybe user is not an appropriate name - change to employee
 */
class User
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private string $name;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private string $surname;

    /**
     * @ORM\Column(type="integer")
     */
    private int $baseSalary;

    /**
     * @ORM\Column(type="date_immutable")
     */
    private \DateTimeImmutable $startedWorkOn;

    /**
     * @ORM\ManyToOne(targetEntity="App\Company\Domain\Entity\Department", inversedBy="users")
     */
    private Department $department;

    public function __construct(
        string $name,
        string $surname,
        int $baseSalary,
        \DateTimeImmutable $startedWorkOn,
        Department $department
    ) {
        $this->name = $name;
        $this->surname = $surname;
        $this->baseSalary = $baseSalary;
        $this->department = $department;
        $this->startedWorkOn = $startedWorkOn;
    }
}
