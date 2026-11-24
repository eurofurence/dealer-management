<?php

namespace App\Entity;

use App\Enum\DealershipRoleEnum;
use App\Repository\DealershipInvitationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Invitation codes created by the main dealer of a dealership to invite shares and assistants.
 *
 * They are a one-time auto-expiring token, so they can be used only once and are then deleted.
 */
#[ORM\Entity(repositoryClass: DealershipInvitationRepository::class)]
class DealershipInvitation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Dealership|null The dealership this invitation is for.
     */
    #[ORM\ManyToOne(inversedBy: 'dealershipInvitations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Dealership $dealership = null;

    /**
     * @var string|null The random generated code.
     */
    #[ORM\Column(length: 63)]
    private ?string $code = null;

    /**
     * @var DealershipRoleEnum|null The role this invitation grants within the dealership.
     *
     * For the main use case, this can only be share or assistant.
     * But if a dealership has no main dealer, this could still be used, e.g. by admins to assign by invitation.
     * TODO: Check if this is actually a use case and if it's worth supporting.
     */
    #[ORM\Column(enumType: DealershipRoleEnum::class)]
    private ?DealershipRoleEnum $role = null;

    /**
     * @var \DateTimeImmutable|null If set, this invitation will expire at the given date and time.
     *
     * Should be checked upon redemption and deleted if expired (and/or by regular cleanup jobs).
     */
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $expiresAt = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function getRole(): ?DealershipRoleEnum
    {
        return $this->role;
    }

    public function setRole(DealershipRoleEnum $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function getExpiresAt(): ?\DateTimeImmutable
    {
        return $this->expiresAt;
    }

    public function setExpiresAt(?\DateTimeImmutable $expiresAt): static
    {
        $this->expiresAt = $expiresAt;

        return $this;
    }
}
