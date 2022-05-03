<?php
declare(strict_types=1);
namespace App\Company\Domain\Entity;

use App\Company\Domain\BonusType;
use App\Company\Domain\Exception\CreateDepartmentException;
use App\Company\Infrastructure\DoctrineDepartmentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;

/**
 * @Entity
 * @Table(name="departments")
 * @ORM\Entity(repositoryClass=DoctrineDepartmentRepository::class)
 * Future improvement - moved mappings to XML to avoid Infrastructure namespace in Domain
 */
class Department
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=100)
     * Future improvement - unique name and domain exception during creation
     */
    private string $name;

    /**
     * @ORM\Column(type="smallint")
     */
    private int $bonusType;

    /**
     * @ORM\Column(type="integer")
     */
    private int $bonusValue;

    /**
     * @ORM\OneToMany(targetEntity="App\Company\Domain\Entity\User", mappedBy="department")
     */
    private $users;

    private function __construct(string $name, BonusType $bonusType, int $bonusValue)
    {
        $this->name = $name;
        $this->bonusType = $bonusType->value;
        $this->bonusValue = $bonusValue;
        $this->users = new ArrayCollection();
    }

    public static function createWithConstantBonus(string $name, int $bonusValue): self
    {
        return new self($name, BonusType::CONSTANT, $bonusValue);
    }

    /** Future improvement - $percentageValue as Value Object */
    public static function createWithPercentageBonus(string $name, int $percentageValue): self
    {
        if ($percentageValue < 1 || $percentageValue > 100) {
            throw CreateDepartmentException::fromIncorrectPercentageValue();
        }

        return new self($name, BonusType::PERCENTAGE, $percentageValue);
    }
}
