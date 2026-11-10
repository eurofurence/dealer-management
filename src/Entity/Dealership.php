<?php

namespace App\Entity;

use App\Repository\DealershipRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DealershipRepository::class)]
class Dealership
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'mainDealership', cascade: ['persist', 'remove'])]
    private ?DealerApplication $mainDealer = null;

    /**
     * @var Collection<int, DealerApplication>
     */
    #[ORM\OneToMany(targetEntity: DealerApplication::class, mappedBy: 'shareDealership')]
    private Collection $shareDealers;

    /**
     * @var Collection<int, AssistantApplication>
     */
    #[ORM\OneToMany(targetEntity: AssistantApplication::class, mappedBy: 'dealership')]
    private Collection $assistants;

    #[ORM\ManyToOne(inversedBy: 'requestingDealerships')]
    private ?TableType $requestedTableType = null;

    #[ORM\OneToOne(inversedBy: 'offeredDealership', cascade: ['persist', 'remove'])]
    private ?Table $offeredTable = null;

    #[ORM\OneToOne(inversedBy: 'assignedDealership', cascade: ['persist', 'remove'])]
    private ?Table $assignedTable = null;

    public function __construct()
    {
        $this->shareDealers = new ArrayCollection();
        $this->assistants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMainDealer(): ?DealerApplication
    {
        return $this->mainDealer;
    }

    public function setMainDealer(?DealerApplication $mainDealer): static
    {
        $this->mainDealer = $mainDealer;

        return $this;
    }

    /**
     * @return Collection<int, DealerApplication>
     */
    public function getShareDealers(): Collection
    {
        return $this->shareDealers;
    }

    public function addShareDealer(DealerApplication $shareDealer): static
    {
        if (!$this->shareDealers->contains($shareDealer)) {
            $this->shareDealers->add($shareDealer);
            $shareDealer->setShareDealership($this);
        }

        return $this;
    }

    public function removeShareDealer(DealerApplication $shareDealer): static
    {
        if ($this->shareDealers->removeElement($shareDealer)) {
            // set the owning side to null (unless already changed)
            if ($shareDealer->getShareDealership() === $this) {
                $shareDealer->setShareDealership(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, AssistantApplication>
     */
    public function getAssistants(): Collection
    {
        return $this->assistants;
    }

    public function addAssistant(AssistantApplication $assistant): static
    {
        if (!$this->assistants->contains($assistant)) {
            $this->assistants->add($assistant);
            $assistant->setDealership($this);
        }

        return $this;
    }

    public function removeAssistant(AssistantApplication $assistant): static
    {
        if ($this->assistants->removeElement($assistant)) {
            // set the owning side to null (unless already changed)
            if ($assistant->getDealership() === $this) {
                $assistant->setDealership(null);
            }
        }

        return $this;
    }

    public function getRequestedTableType(): ?TableType
    {
        return $this->requestedTableType;
    }

    public function setRequestedTableType(?TableType $requestedTableType): static
    {
        $this->requestedTableType = $requestedTableType;

        return $this;
    }

    public function getOfferedTable(): ?Table
    {
        return $this->offeredTable;
    }

    public function setOfferedTable(?Table $offeredTable): static
    {
        $this->offeredTable = $offeredTable;

        return $this;
    }

    public function getAssignedTable(): ?Table
    {
        return $this->assignedTable;
    }

    public function setAssignedTable(?Table $assignedTable): static
    {
        $this->assignedTable = $assignedTable;

        return $this;
    }
}
