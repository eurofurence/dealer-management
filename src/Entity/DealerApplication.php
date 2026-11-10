<?php

namespace App\Entity;

use App\Repository\DealerApplicationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DealerApplicationRepository::class)]
class DealerApplication
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'dealerApplications')]
    private ?User $applicant = null;

    #[ORM\OneToOne(mappedBy: 'mainDealer', cascade: ['persist', 'remove'])]
    private ?Dealership $mainDealership = null;

    #[ORM\ManyToOne(inversedBy: 'shareDealers')]
    private ?Dealership $shareDealership = null;

    #[ORM\OneToOne(inversedBy: 'dealerApplication', cascade: ['persist', 'remove'])]
    private ?PublicProfile $publicProfile = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getApplicant(): ?User
    {
        return $this->applicant;
    }

    public function setApplicant(?User $applicant): static
    {
        $this->applicant = $applicant;

        return $this;
    }

    public function getMainDealership(): ?Dealership
    {
        return $this->mainDealership;
    }

    public function setMainDealership(?Dealership $mainDealership): static
    {
        // unset the owning side of the relation if necessary
        if ($mainDealership === null && $this->mainDealership !== null) {
            $this->mainDealership->setMainDealer(null);
        }

        // set the owning side of the relation if necessary
        if ($mainDealership !== null && $mainDealership->getMainDealer() !== $this) {
            $mainDealership->setMainDealer($this);
        }

        $this->mainDealership = $mainDealership;

        return $this;
    }

    public function getShareDealership(): ?Dealership
    {
        return $this->shareDealership;
    }

    public function setShareDealership(?Dealership $shareDealership): static
    {
        $this->shareDealership = $shareDealership;

        return $this;
    }

    /**
     * @return Dealership|null The dealership associated with this application.
     * In the invalid case of multiple association, the main dealership is returned.
     */
    public function getDealership(): ?Dealership
    {
        return $this->mainDealership ?? $this->shareDealership;
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
