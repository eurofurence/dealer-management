<?php

namespace App\Entity;

use App\Repository\TableTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TableTypeRepository::class)]
class TableType
{
    #[ORM\Id]
    #[ORM\Column(length: 63)]
    private ?string $id = null;

    /**
     * @var Collection<int, Dealership>
     */
    #[ORM\OneToMany(targetEntity: Dealership::class, mappedBy: 'requestedTableType')]
    private Collection $requestingDealerships;

    /**
     * @var Collection<int, Table>
     */
    #[ORM\OneToMany(targetEntity: Table::class, mappedBy: 'type')]
    private Collection $tables;

    public function __construct()
    {
        $this->requestingDealerships = new ArrayCollection();
        $this->tables = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $id): static
    {
        $this->id = $id;

        return $this;
    }


    /**
     * @return Collection<int, Dealership>
     */
    public function getRequestingDealerships(): Collection
    {
        return $this->requestingDealerships;
    }

    public function addRequestingDealership(Dealership $requestingDealership): static
    {
        if (!$this->requestingDealerships->contains($requestingDealership)) {
            $this->requestingDealerships->add($requestingDealership);
            $requestingDealership->setRequestedTableType($this);
        }

        return $this;
    }

    public function removeRequestingDealership(Dealership $requestingDealership): static
    {
        if ($this->requestingDealerships->removeElement($requestingDealership)) {
            // set the owning side to null (unless already changed)
            if ($requestingDealership->getRequestedTableType() === $this) {
                $requestingDealership->setRequestedTableType(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Table>
     */
    public function getTables(): Collection
    {
        return $this->tables;
    }

    public function addTable(Table $table): static
    {
        if (!$this->tables->contains($table)) {
            $this->tables->add($table);
            $table->setType($this);
        }

        return $this;
    }

    public function removeTable(Table $table): static
    {
        if ($this->tables->removeElement($table)) {
            // set the owning side to null (unless already changed)
            if ($table->getType() === $this) {
                $table->setType(null);
            }
        }

        return $this;
    }
}
