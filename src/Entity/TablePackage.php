<?php

namespace App\Entity;

use App\Repository\TableTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * An offered variant of type of logical table.
 * It represents a purchasable category (e.g. nominal size and cost).
 *
 * We intentionally do not relate any actual final shape directly in this entity.
 * It can be given in the description, but the actual shape is individual per Table entity.
 *
 * TODO: Define how the number of shares and assistants is limited (was "seats" before).
 */
#[ORM\Entity(repositoryClass: TableTypeRepository::class)]
class TablePackage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Collection<int, Dealership> Dealerships that requested this table type during application phase.
     */
    #[ORM\OneToMany(targetEntity: Dealership::class, mappedBy: 'requestedTablePackage')]
    private Collection $requestingDealerships;

    /**
     * @var Collection<int, Dealership> Dealerships that got this package assigned to pay for (if accepted).
     */
    #[ORM\OneToMany(targetEntity: Dealership::class, mappedBy: 'assignedTablePackage')]
    private Collection $assignedToDealerships;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var int|null Cost in euro-cents if this table type is available for purchase.
     */
    #[ORM\Column(nullable: true)]
    private ?int $price = null;

    /**
     * @var bool Allow applicants to request this table type for their dealership.
     */
    #[ORM\Column]
    private bool $requestableForDealership = false;

    /**
     * @var string|null The key to identify this package in the payment system.
     */
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $productKey = null;


    public function __construct()
    {
        $this->requestingDealerships = new ArrayCollection();
        $this->assignedToDealerships = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Dealership>
     */
    public function getRequestingDealerships(): Collection
    {
        return $this->requestingDealerships;
    }

    /**
     * @return Collection<int, Dealership>
     */
    public function getAssignedToDealerships(): Collection
    {
        return $this->assignedToDealerships;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(?int $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function isRequestableForDealership(): ?bool
    {
        return $this->requestableForDealership;
    }

    public function setRequestableForDealership(bool $requestableForDealership): static
    {
        $this->requestableForDealership = $requestableForDealership;

        return $this;
    }

    public function getProductKey(): ?string
    {
        return $this->productKey;
    }

    public function setProductKey(?string $productKey): static
    {
        $this->productKey = $productKey;

        return $this;
    }

}
