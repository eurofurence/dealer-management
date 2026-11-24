<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Represents one actual person which logs in via the external IDP.
 * Every user in the system has this entity.
 *
 * The actual roles users can take on in the Dealer's Den (Delaer/Share or Assistant) are
 * represented by related entities Dealer and Assistant and are mutually exclusive.
 * We do NOT want to enforce this on the database level, since a user might want to change back
 * to a different role without loosing their original data. We also allow for multiple instances
 * per role to support e.g. versioning/drafting in the future.
 */
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'app_user')]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Collection<int, Dealer> The entities describing this users's role as a Dealer.
     */
    #[ORM\OneToMany(targetEntity: Dealer::class, mappedBy: 'user')]
    private Collection $dealers;

    /**
     * @var Collection<int, Assistant> The entities describing this users's role as an Assistant.
     */
    #[ORM\OneToMany(targetEntity: Assistant::class, mappedBy: 'user')]
    private Collection $assistants;

    /**
     * @var Collection<int, PublicProfile> The public profiles that this user created/owns.
     */
    #[ORM\OneToMany(targetEntity: PublicProfile::class, mappedBy: 'user')]
    private Collection $publicProfiles;

    /**
     * @var Collection<int, ArtistAlleySession> The artist alley sessions for this user.
     */
    #[ORM\OneToMany(targetEntity: ArtistAlleySession::class, mappedBy: 'user')]
    private Collection $artistAlleySessions;

    /**
     * @var Collection<int, Keyword>
     */
    #[ORM\OneToMany(targetEntity: Keyword::class, mappedBy: 'submittedBy')]
    private Collection $submittedKeywords;

    public function __construct()
    {
        $this->dealers = new ArrayCollection();
        $this->assistants = new ArrayCollection();
        $this->publicProfiles = new ArrayCollection();
        $this->artistAlleySessions = new ArrayCollection();
        $this->submittedKeywords = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Dealer>
     */
    public function getDealers(): Collection
    {
        return $this->dealers;
    }

    public function addDealerApplication(Dealer $dealerApplication): static
    {
        if (!$this->dealers->contains($dealerApplication)) {
            $this->dealers->add($dealerApplication);
            $dealerApplication->setUser($this);
        }

        return $this;
    }

    public function removeDealerApplication(Dealer $dealerApplication): static
    {
        if ($this->dealers->removeElement($dealerApplication)) {
            // set the owning side to null (unless already changed)
            if ($dealerApplication->getUser() === $this) {
                $dealerApplication->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Assistant>
     */
    public function getAssistants(): Collection
    {
        return $this->assistants;
    }

    public function addAssistantApplication(Assistant $assistantApplication): static
    {
        if (!$this->assistants->contains($assistantApplication)) {
            $this->assistants->add($assistantApplication);
            $assistantApplication->setUser($this);
        }

        return $this;
    }

    public function removeAssistantApplication(Assistant $assistantApplication): static
    {
        if ($this->assistants->removeElement($assistantApplication)) {
            // set the owning side to null (unless already changed)
            if ($assistantApplication->getUser() === $this) {
                $assistantApplication->setUser(null);
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
            $publicProfile->setUser($this);
        }

        return $this;
    }

    public function removePublicProfile(PublicProfile $publicProfile): static
    {
        if ($this->publicProfiles->removeElement($publicProfile)) {
            // set the owning side to null (unless already changed)
            if ($publicProfile->getUser() === $this) {
                $publicProfile->setUser(null);
            }
        }

        return $this;
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
            $artistAlleyApplication->setUser($this);
        }

        return $this;
    }

    public function removeArtistAlleyApplication(ArtistAlleySession $artistAlleyApplication): static
    {
        if ($this->artistAlleySessions->removeElement($artistAlleyApplication)) {
            // set the owning side to null (unless already changed)
            if ($artistAlleyApplication->getUser() === $this) {
                $artistAlleyApplication->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Keyword>
     */
    public function getSubmittedKeywords(): Collection
    {
        return $this->submittedKeywords;
    }

    public function addSubmittedKeyword(Keyword $submittedKeyword): static
    {
        if (!$this->submittedKeywords->contains($submittedKeyword)) {
            $this->submittedKeywords->add($submittedKeyword);
            $submittedKeyword->setSubmittedBy($this);
        }

        return $this;
    }

    public function removeSubmittedKeyword(Keyword $submittedKeyword): static
    {
        if ($this->submittedKeywords->removeElement($submittedKeyword)) {
            // set the owning side to null (unless already changed)
            if ($submittedKeyword->getSubmittedBy() === $this) {
                $submittedKeyword->setSubmittedBy(null);
            }
        }

        return $this;
    }
}
