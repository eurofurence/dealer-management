<?php

namespace App\Entity;

use App\Repository\ArtistAlleyApplicationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArtistAlleyApplicationRepository::class)]
class ArtistAlleyApplication
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'artistAlleyApplications')]
    private ?User $applicant = null;

    #[ORM\ManyToOne(inversedBy: 'artistAlleyApplications')]
    private ?Table $claimedTable = null;

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

    public function getClaimedTable(): ?Table
    {
        return $this->claimedTable;
    }

    public function setClaimedTable(?Table $claimedTable): static
    {
        $this->claimedTable = $claimedTable;

        return $this;
    }
}
