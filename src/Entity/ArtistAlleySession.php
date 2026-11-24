<?php

namespace App\Entity;

use App\Repository\ArtistAlleyApplicationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * An artist claiming a table for a limited amount of time.
 * This tracks the status of claiming of a table, the approval/rejection, time keeping and the linked PublicProfile.
 */
#[ORM\Entity(repositoryClass: ArtistAlleyApplicationRepository::class)]
class ArtistAlleySession
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var User|null The artist claiming the table and responsible for this session.
     */
    #[ORM\ManyToOne(inversedBy: 'artistAlleySessions')]
    private ?User $user = null;

    /**
     * @var Table|null The table claimed for this session.
     */
    #[ORM\ManyToOne(inversedBy: 'artistAlleySessions')]
    private ?Table $claimedTable = null;

    /**
     * @var PublicProfile|null The profile displayed for this session.
     */
    #[ORM\ManyToOne(inversedBy: 'artistAlleySessions')]
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

    public function getClaimedTable(): ?Table
    {
        return $this->claimedTable;
    }

    public function setClaimedTable(?Table $claimedTable): static
    {
        $this->claimedTable = $claimedTable;

        return $this;
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
