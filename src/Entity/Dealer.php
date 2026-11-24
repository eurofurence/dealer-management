<?php

namespace App\Entity;

use App\Repository\DealerApplicationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Tracks the role of one user as a Dealer or Share in a Dealership.
 *
 * Can be either MAIN -OR- SHARE of a Dealership.
 * TODO: Add a setter-side and database-side uniqueness check that a dealer application can either be MAIN or SHARE.
 */
#[ORM\Entity(repositoryClass: DealerApplicationRepository::class)]
class Dealer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var User|null The user who is the Dealer or Share.
     */
    #[ORM\ManyToOne(inversedBy: 'dealers')]
    private ?User $user = null;

    /**
     * @var Dealership|null The dealership where this application is the MAIN.
     */
    #[ORM\OneToOne(mappedBy: 'mainDealer', cascade: ['persist', 'remove'])]
    private ?Dealership $mainOfDealership = null;

    /**
     * @var Dealership|null The dealership where this application is a SHARE.
     */
    #[ORM\ManyToOne(inversedBy: 'shareDealers')]
    private ?Dealership $shareOfDealership = null;

    /**
     * @var PublicProfile|null The public profile representing this dealer.
     */
    #[ORM\ManyToOne(inversedBy: 'dealers')]
    private ?PublicProfile $publicProfile = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getMainOfDealership(): ?Dealership
    {
        return $this->mainOfDealership;
    }

    public function setMainOfDealership(?Dealership $mainOfDealership): static
    {
        // unset the owning side of the relation if necessary
        if ($mainOfDealership === null && $this->mainOfDealership !== null) {
            $this->mainOfDealership->setMainDealer(null);
        }

        // set the owning side of the relation if necessary
        if ($mainOfDealership !== null && $mainOfDealership->getMainDealer() !== $this) {
            $mainOfDealership->setMainDealer($this);
        }

        $this->mainOfDealership = $mainOfDealership;

        return $this;
    }

    public function getShareOfDealership(): ?Dealership
    {
        return $this->shareOfDealership;
    }

    public function setShareOfDealership(?Dealership $shareOfDealership): static
    {
        $this->shareOfDealership = $shareOfDealership;

        return $this;
    }

    /**
     * @return Dealership|null The dealership associated with this application.
     * In the invalid case of multiple association, the main dealership is returned.
     */
    public function getDealership(): ?Dealership
    {
        return $this->mainOfDealership ?? $this->shareOfDealership;
    }

    public function getPublicProfile(): ?PublicProfile
    {
        return $this->publicProfile;
    }

    public function setPublicProfile(?PublicProfile $publicProfile): static
    {
        $this->publicProfile = $publicProfile;

        return $this;
    }
}
