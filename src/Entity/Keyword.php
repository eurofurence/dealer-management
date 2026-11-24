<?php

namespace App\Entity;

use App\Repository\KeywordRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Keywords to be used on public profiles.
 */
#[ORM\Entity(repositoryClass: KeywordRepository::class)]
class Keyword
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var string|null The public visible display name of this keyword.
     */
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var bool This is a hero keyword. Heros are shown first, identifying broader categories.
     */
    #[ORM\Column]
    private bool $isHero = false;

    /**
     * @var bool This is a keyword that is only shown when and where after dark content is visible.
     */
    #[ORM\Column]
    private bool $isAfterDark = false;

    /**
     * @var bool|null Users can select this keyword themselves.
     */
    #[ORM\Column]
    private bool $isUserSelectable = true;

    /**
     * @var bool|null Mark this keyword as blocked.
     *
     * Important: This does not necessarily mean the word is deemed inappropriate.
     * It could also be used to block too generic keywords or alternative spellings/words.
     *
     * Blocked keywords can never be selected and are not shown.
     * This also serves to prevent the keyword from being created/suggested again.
     *
     * FUTURE: Could also implement something like "is alias for" to still allow in searches.
     */
    #[ORM\Column]
    private bool $isBlocked = false;

    /**
     * @var User|null The user who submitted this keyword, if not defined by system/admins.
     */
    #[ORM\ManyToOne(inversedBy: 'submittedKeywords')]
    private ?User $submittedBy = null;

    /**
     * @var Collection<int, PublicProfile> All public profiles that use this keyword.
     */
    #[ORM\ManyToMany(targetEntity: PublicProfile::class, mappedBy: 'keywords')]
    private Collection $publicProfiles;

    public function __construct()
    {
        $this->publicProfiles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function isHero(): ?bool
    {
        return $this->isHero;
    }

    public function setIsHero(bool $isHero): static
    {
        $this->isHero = $isHero;

        return $this;
    }

    public function isAfterDark(): ?bool
    {
        return $this->isAfterDark;
    }

    public function setIsAfterDark(bool $isAfterDark): static
    {
        $this->isAfterDark = $isAfterDark;

        return $this;
    }

    public function isUserSelectable(): ?bool
    {
        return $this->isUserSelectable;
    }

    public function setIsUserSelectable(bool $isUserSelectable): static
    {
        $this->isUserSelectable = $isUserSelectable;

        return $this;
    }

    public function isBlocked(): ?bool
    {
        return $this->isBlocked;
    }

    public function setIsBlocked(bool $isBlocked): static
    {
        $this->isBlocked = $isBlocked;

        return $this;
    }

    public function getSubmittedBy(): ?User
    {
        return $this->submittedBy;
    }

    public function setSubmittedBy(?User $submittedBy): static
    {
        $this->submittedBy = $submittedBy;

        return $this;
    }

    /**
     * @return Collection<int, PublicProfile>
     */
    public function getPublicProfiles(): Collection
    {
        return $this->publicProfiles;
    }

}
