<?php

namespace App\Entity;

use App\Repository\TableRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TableRepository::class)]
#[ORM\Table(name: 'app_table')]
class Table
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'tables')]
    private ?TableType $type = null;

    #[ORM\OneToOne(mappedBy: 'offeredTable', cascade: ['persist', 'remove'])]
    private ?Dealership $offeredDealership = null;

    #[ORM\OneToOne(mappedBy: 'assignedTable', cascade: ['persist', 'remove'])]
    private ?Dealership $assignedDealership = null;

    /**
     * @var Collection<int, ArtistAlleyApplication>
     */
    #[ORM\OneToMany(targetEntity: ArtistAlleyApplication::class, mappedBy: 'claimedTable')]
    private Collection $artistAlleyApplications;

    public function __construct()
    {
        $this->artistAlleyApplications = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?TableType
    {
        return $this->type;
    }

    public function setType(?TableType $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getOfferedDealership(): ?Dealership
    {
        return $this->offeredDealership;
    }

    public function getAssignedDealership(): ?Dealership
    {
        return $this->assignedDealership;
    }

    /**
     * @return Collection<int, ArtistAlleyApplication>
     */
    public function getArtistAlleyApplications(): Collection
    {
        return $this->artistAlleyApplications;
    }

    public function addArtistAlleyApplication(ArtistAlleyApplication $artistAlleyApplication): static
    {
        if (!$this->artistAlleyApplications->contains($artistAlleyApplication)) {
            $this->artistAlleyApplications->add($artistAlleyApplication);
            $artistAlleyApplication->setClaimedTable($this);
        }

        return $this;
    }

    public function removeArtistAlleyApplication(ArtistAlleyApplication $artistAlleyApplication): static
    {
        if ($this->artistAlleyApplications->removeElement($artistAlleyApplication)) {
            // set the owning side to null (unless already changed)
            if ($artistAlleyApplication->getClaimedTable() === $this) {
                $artistAlleyApplication->setClaimedTable(null);
            }
        }

        return $this;
    }
}
