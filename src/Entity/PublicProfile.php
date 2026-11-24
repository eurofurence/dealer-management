<?php

namespace App\Entity;

use App\Repository\PublicProfileRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * A public profile that users can use to represent their offerings.
 * We use a unified profile for the roles of dealers and shares and for the artist alley.
 */
#[ORM\Entity(repositoryClass: PublicProfileRepository::class)]
class PublicProfile
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var User|null The owner/creator of this profile.
     */
    #[ORM\ManyToOne(inversedBy: 'publicProfiles')]
    private ?User $user = null;

    /**
     * @var Collection<int, ArtistAlleySession> The artist alley sessions that use this profile.
     */
    #[ORM\OneToMany(targetEntity: ArtistAlleySession::class, mappedBy: 'publicProfile')]
    private Collection $artistAlleySessions;

    /**
     * @var Collection<int, Dealer> The dealer roles use that this profile.
     */
    #[ORM\OneToMany(targetEntity: Dealer::class, mappedBy: 'publicProfile')]
    private Collection $dealers;

    /**
     * @var Collection<int, Keyword>
     */
    #[ORM\ManyToMany(targetEntity: Keyword::class, inversedBy: 'publicProfiles')]
    private Collection $keywords;

    public function __construct()
    {
        $this->artistAlleySessions = new ArrayCollection();
        $this->dealers = new ArrayCollection();
        $this->keywords = new ArrayCollection();
    }


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

    /**
     * @return Collection<int, ArtistAlleySession>
     */
    public function getArtistAlleySessions(): Collection
    {
        return $this->artistAlleySessions;
    }

    public function addArtistAlleySession(ArtistAlleySession $artistAlleySession): static
    {
        if (!$this->artistAlleySessions->contains($artistAlleySession)) {
            $this->artistAlleySessions->add($artistAlleySession);
            $artistAlleySession->setPublicProfile($this);
        }

        return $this;
    }

    public function removeArtistAlleySession(ArtistAlleySession $artistAlleySession): static
    {
        if ($this->artistAlleySessions->removeElement($artistAlleySession)) {
            // set the owning side to null (unless already changed)
            if ($artistAlleySession->getPublicProfile() === $this) {
                $artistAlleySession->setPublicProfile(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Dealer>
     */
    public function getDealers(): Collection
    {
        return $this->dealers;
    }

    public function addDealer(Dealer $dealer): static
    {
        if (!$this->dealers->contains($dealer)) {
            $this->dealers->add($dealer);
            $dealer->setPublicProfile($this);
        }

        return $this;
    }

    public function removeDealer(Dealer $dealer): static
    {
        if ($this->dealers->removeElement($dealer)) {
            // set the owning side to null (unless already changed)
            if ($dealer->getPublicProfile() === $this) {
                $dealer->setPublicProfile(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Keyword>
     */
    public function getKeywords(): Collection
    {
        return $this->keywords;
    }

    public function addKeyword(Keyword $keyword): static
    {
        if (!$this->keywords->contains($keyword)) {
            $this->keywords->add($keyword);
        }

        return $this;
    }

    public function removeKeyword(Keyword $keyword): static
    {
        $this->keywords->removeElement($keyword);

        return $this;
    }

}
