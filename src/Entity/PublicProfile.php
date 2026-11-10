<?php

namespace App\Entity;

use App\Repository\PublicProfileRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PublicProfileRepository::class)]
class PublicProfile
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'publicProfiles')]
    private ?User $owner = null;

    #[ORM\OneToOne(mappedBy: 'publicProfile', cascade: ['persist', 'remove'])]
    private ?DealerApplication $dealerApplication = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): static
    {
        $this->owner = $owner;

        return $this;
    }

    public function getDealerApplication(): ?DealerApplication
    {
        return $this->dealerApplication;
    }

    public function setDealerApplication(?DealerApplication $dealerApplication): static
    {
        // unset the owning side of the relation if necessary
        if ($dealerApplication === null && $this->dealerApplication !== null) {
            $this->dealerApplication->setPublicProfile(null);
        }

        // set the owning side of the relation if necessary
        if ($dealerApplication !== null && $dealerApplication->getPublicProfile() !== $this) {
            $dealerApplication->setPublicProfile($this);
        }

        $this->dealerApplication = $dealerApplication;

        return $this;
    }
}
