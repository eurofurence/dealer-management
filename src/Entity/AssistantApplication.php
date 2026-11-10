<?php

namespace App\Entity;

use App\Repository\AssistantApplicationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AssistantApplicationRepository::class)]
class AssistantApplication
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'assistantApplications')]
    private ?User $applicant = null;

    #[ORM\ManyToOne(inversedBy: 'assistants')]
    private ?Dealership $dealership = null;

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

    public function getDealership(): ?Dealership
    {
        return $this->dealership;
    }

    public function setDealership(?Dealership $dealership): static
    {
        $this->dealership = $dealership;

        return $this;
    }
}
