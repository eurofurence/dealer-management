<?php

namespace App\Entity;

use App\Repository\TableRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * A logical table which is a group of one or more physical tables.
 * This is equivalent to what is usually meant when people refer to "a table".
 * Also serves as a location designator (via tableNumber).
 */
#[ORM\Entity(repositoryClass: TableRepository::class)]
#[ORM\Table(name: 'logical_table')]
class Table
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Collection<int, ArtistAlleySession> Artist alley sessions which have/had claimed this table.
     */
    #[ORM\OneToMany(targetEntity: ArtistAlleySession::class, mappedBy: 'claimedTable')]
    private Collection $artistAlleySessions;

    /**
     * @var Dealership|null The dealership which is assigned to this table (or none).
     */
    #[ORM\OneToOne(mappedBy: 'table', cascade: ['persist', 'remove'])]
    private ?Dealership $dealership = null;

    #[ORM\Column]
    private bool $afterDark = false;

    public function __construct()
    {
        $this->artistAlleySessions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, ArtistAlleySession>
     */
    public function getArtistAlleySessions(): Collection
    {
        return $this->artistAlleySessions;
    }

    public function addArtistAlleyApplication(ArtistAlleySession $artistAlleyApplication): static
    {
        if (!$this->artistAlleySessions->contains($artistAlleyApplication)) {
            $this->artistAlleySessions->add($artistAlleyApplication);
            $artistAlleyApplication->setClaimedTable($this);
        }

        return $this;
    }

    public function removeArtistAlleyApplication(ArtistAlleySession $artistAlleyApplication): static
    {
        if ($this->artistAlleySessions->removeElement($artistAlleyApplication)) {
            // set the owning side to null (unless already changed)
            if ($artistAlleyApplication->getClaimedTable() === $this) {
                $artistAlleyApplication->setClaimedTable(null);
            }
        }

        return $this;
    }

    public function getDealership(): ?Dealership
    {
        return $this->dealership;
    }

    public function setDealership(?Dealership $dealership): static
    {
        // unset the owning side of the relation if necessary
        if ($dealership === null && $this->dealership !== null) {
            $this->dealership->setTable(null);
        }

        // set the owning side of the relation if necessary
        if ($dealership !== null && $dealership->getTable() !== $this) {
            $dealership->setTable($this);
        }

        $this->dealership = $dealership;

        return $this;
    }

    public function isAfterDark(): bool
    {
        return $this->afterDark;
    }

    public function setAfterDark(bool $afterDark): static
    {
        $this->afterDark = $afterDark;

        return $this;
    }
}
