<?php

namespace App\Entity;

use App\Repository\AssistantApplicationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AssistantApplicationRepository::class)]
class Assistant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'assistants')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'assistants')]
    private ?Dealership $dealership = null;

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
