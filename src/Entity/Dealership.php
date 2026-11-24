<?php

namespace App\Entity;

use App\Enum\YesNoAnyEnum;
use App\Repository\DealershipRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Information about a (solo or joint) dealership.
 *
 * One Dealership consists of one MAIN DealerApplication and zero or more SHARE DealerApplications.
 * It can also have zero or more AssistantApplications.
 */
#[ORM\Entity(repositoryClass: DealershipRepository::class)]
class Dealership
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Dealer|null The main dealer for this dealership who is also managing it.
     */
    #[ORM\OneToOne(inversedBy: 'mainOfDealership', cascade: ['persist', 'remove'])]
    private ?Dealer $mainDealer = null;

    /**
     * @var Collection<int, Dealer> The share dealers, if any.
     */
    #[ORM\OneToMany(targetEntity: Dealer::class, mappedBy: 'shareOfDealership')]
    private Collection $shareDealers;

    /**
     * @var Collection<int, Assistant> The assigned assistants, if any.
     */
    #[ORM\OneToMany(targetEntity: Assistant::class, mappedBy: 'dealership')]
    private Collection $assistants;

    /**
     * @var TablePackage|null The table package/category requested for this dealership during application phase.
     */
    #[ORM\ManyToOne(inversedBy: 'requestingDealerships')]
    private ?TablePackage $requestedTablePackage = null;

    /**
     * @var TablePackage|null The table package/category the dealership actually has to pay (if accepted).
     */
    #[ORM\ManyToOne(inversedBy: 'assignedToDealerships')]
    private ?TablePackage $assignedTablePackage = null;


    /**
     * @var Table|null The table where this dealership is located.
     *
     * Depending on the application status, this is the table offered by the curators,
     * and then becomes the asssigned table used during the convention.
     */
    #[ORM\OneToOne(inversedBy: 'dealership', cascade: ['persist', 'remove'])]
    private ?Table $table = null;

    /**
     * @var YesNoAnyEnum|null The preference for being placed in after-dark area.
     */
    #[ORM\Column(nullable: true, enumType: YesNoAnyEnum::class)]
    private ?YesNoAnyEnum $afterDarkPreference = null;

    /**
     * @var Collection<int, DealershipInvitation> Invitation codes created by this dealership.
     */
    #[ORM\OneToMany(targetEntity: DealershipInvitation::class, mappedBy: 'dealership', orphanRemoval: true)]
    private Collection $dealershipInvitations;


    public function __construct()
    {
        $this->shareDealers = new ArrayCollection();
        $this->assistants = new ArrayCollection();
        $this->dealershipInvitations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMainDealer(): ?Dealer
    {
        return $this->mainDealer;
    }

    public function setMainDealer(?Dealer $mainDealer): static
    {
        $this->mainDealer = $mainDealer;

        return $this;
    }

    /**
     * @return Collection<int, Dealer>
     */
    public function getShareDealers(): Collection
    {
        return $this->shareDealers;
    }

    public function addShareDealer(Dealer $shareDealer): static
    {
        if (!$this->shareDealers->contains($shareDealer)) {
            $this->shareDealers->add($shareDealer);
            $shareDealer->setShareOfDealership($this);
        }

        return $this;
    }

    public function removeShareDealer(Dealer $shareDealer): static
    {
        if ($this->shareDealers->removeElement($shareDealer)) {
            // set the owning side to null (unless already changed)
            if ($shareDealer->getShareOfDealership() === $this) {
                $shareDealer->setShareOfDealership(null);
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

    public function addAssistant(Assistant $assistant): static
    {
        if (!$this->assistants->contains($assistant)) {
            $this->assistants->add($assistant);
            $assistant->setDealership($this);
        }

        return $this;
    }

    public function removeAssistant(Assistant $assistant): static
    {
        if ($this->assistants->removeElement($assistant)) {
            // set the owning side to null (unless already changed)
            if ($assistant->getDealership() === $this) {
                $assistant->setDealership(null);
            }
        }

        return $this;
    }

    public function getRequestedTablePackage(): ?TablePackage
    {
        return $this->requestedTablePackage;
    }

    public function setRequestedTablePackage(?TablePackage $requestedTablePackage): static
    {
        $this->requestedTablePackage = $requestedTablePackage;

        return $this;
    }

    public function getTable(): ?Table
    {
        return $this->table;
    }

    public function setTable(?Table $table): static
    {
        $this->table = $table;

        return $this;
    }

    public function getAfterDarkPreference(): ?YesNoAnyEnum
    {
        return $this->afterDarkPreference;
    }

    public function setAfterDarkPreference(?YesNoAnyEnum $afterDarkPreference): static
    {
        $this->afterDarkPreference = $afterDarkPreference;

        return $this;
    }

    public function getAssignedTablePackage(): ?TablePackage
    {
        return $this->assignedTablePackage;
    }

    public function setAssignedTablePackage(?TablePackage $assignedTablePackage): static
    {
        $this->assignedTablePackage = $assignedTablePackage;

        return $this;
    }

    /**
     * @return Collection<int, DealershipInvitation>
     */
    public function getDealershipInvitations(): Collection
    {
        return $this->dealershipInvitations;
    }

    public function addDealershipInvitation(DealershipInvitation $dealershipInvitation): static
    {
        if (!$this->dealershipInvitations->contains($dealershipInvitation)) {
            $this->dealershipInvitations->add($dealershipInvitation);
            $dealershipInvitation->setDealership($this);
        }

        return $this;
    }

    public function removeDealershipInvitation(DealershipInvitation $dealershipInvitation): static
    {
        if ($this->dealershipInvitations->removeElement($dealershipInvitation)) {
            // set the owning side to null (unless already changed)
            if ($dealershipInvitation->getDealership() === $this) {
                $dealershipInvitation->setDealership(null);
            }
        }

        return $this;
    }

}
