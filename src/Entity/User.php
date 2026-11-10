<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'app_user')]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Collection<int, DealerApplication>
     */
    #[ORM\OneToMany(targetEntity: DealerApplication::class, mappedBy: 'applicant')]
    private Collection $dealerApplications;

    /**
     * @var Collection<int, AssistantApplication>
     */
    #[ORM\OneToMany(targetEntity: AssistantApplication::class, mappedBy: 'applicant')]
    private Collection $assistantApplications;

    /**
     * @var Collection<int, PublicProfile>
     */
    #[ORM\OneToMany(targetEntity: PublicProfile::class, mappedBy: 'owner')]
    private Collection $publicProfiles;

    public function __construct()
    {
        $this->dealerApplications = new ArrayCollection();
        $this->assistantApplications = new ArrayCollection();
        $this->publicProfiles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, DealerApplication>
     */
    public function getDealerApplications(): Collection
    {
        return $this->dealerApplications;
    }

    public function addDealerApplication(DealerApplication $dealerApplication): static
    {
        if (!$this->dealerApplications->contains($dealerApplication)) {
            $this->dealerApplications->add($dealerApplication);
            $dealerApplication->setApplicant($this);
        }

        return $this;
    }

    public function removeDealerApplication(DealerApplication $dealerApplication): static
    {
        if ($this->dealerApplications->removeElement($dealerApplication)) {
            // set the owning side to null (unless already changed)
            if ($dealerApplication->getApplicant() === $this) {
                $dealerApplication->setApplicant(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, AssistantApplication>
     */
    public function getAssistantApplications(): Collection
    {
        return $this->assistantApplications;
    }

    public function addAssistantApplication(AssistantApplication $assistantApplication): static
    {
        if (!$this->assistantApplications->contains($assistantApplication)) {
            $this->assistantApplications->add($assistantApplication);
            $assistantApplication->setApplicant($this);
        }

        return $this;
    }

    public function removeAssistantApplication(AssistantApplication $assistantApplication): static
    {
        if ($this->assistantApplications->removeElement($assistantApplication)) {
            // set the owning side to null (unless already changed)
            if ($assistantApplication->getApplicant() === $this) {
                $assistantApplication->setApplicant(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PublicProfile>
     */
    public function getPublicProfiles(): Collection
    {
        return $this->publicProfiles;
    }

    public function addPublicProfile(PublicProfile $publicProfile): static
    {
        if (!$this->publicProfiles->contains($publicProfile)) {
            $this->publicProfiles->add($publicProfile);
            $publicProfile->setOwner($this);
        }

        return $this;
    }

    public function removePublicProfile(PublicProfile $publicProfile): static
    {
        if ($this->publicProfiles->removeElement($publicProfile)) {
            // set the owning side to null (unless already changed)
            if ($publicProfile->getOwner() === $this) {
                $publicProfile->setOwner(null);
            }
        }

        return $this;
    }
}
