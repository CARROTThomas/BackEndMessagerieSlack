<?php

namespace App\Entity;

use App\Repository\ProfileRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ProfileRepository::class)]
class Profile
{
    #[Groups(['profile', 'friend', 'message_private_conversation'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(["profile", 'request', 'friend', 'privateConversation:read', 'message_private_conversation', 'groupConversation', 'message_group_conversation'])]
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[Groups('profile')]
    #[ORM\OneToOne(inversedBy: 'profile', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $profileUser = null;

    #[Groups('profile')]
    #[ORM\OneToMany(mappedBy: 'recipient', targetEntity: Request::class)]
    private Collection $requests;

    #[Groups('profile')]
    #[ORM\OneToMany(mappedBy: 'personA', targetEntity: Relation::class, orphanRemoval: true)]
    private Collection $relations;

    #[ORM\OneToMany(mappedBy: 'individualA', targetEntity: PrivateConversation::class, orphanRemoval: true)]
    private Collection $privateConversations;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: PrivateMessage::class)]
    private Collection $privateMessages;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: PrivateResponse::class)]
    private Collection $privateResponses;

    #[ORM\ManyToMany(targetEntity: GroupConversation::class, mappedBy: 'members')]
    private Collection $groupConversations;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: GroupMessage::class)]
    private Collection $groupMessages;

    public function __construct()
    {
        $this->requests = new ArrayCollection();
        $this->relations = new ArrayCollection();
        $this->privateConversations = new ArrayCollection();
        $this->privateMessages = new ArrayCollection();
        $this->privateResponses = new ArrayCollection();
        $this->groupConversations = new ArrayCollection();
        $this->groupMessages = new ArrayCollection();
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

    public function getProfileUser(): ?User
    {
        return $this->profileUser;
    }

    public function setProfileUser(User $profileUser): static
    {
        $this->profileUser = $profileUser;

        return $this;
    }

    /**
     * @return Collection<int, Request>
     */
    public function getRequests(): Collection
    {
        return $this->requests;
    }

    public function addRequest(Request $request): static
    {
        if (!$this->requests->contains($request)) {
            $this->requests->add($request);
            $request->setSender($this);
        }

        return $this;
    }

    public function removeRequest(Request $request): static
    {
        if ($this->requests->removeElement($request)) {
            // set the owning side to null (unless already changed)
            if ($request->getSender() === $this) {
                $request->setSender(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Relation>
     */
    public function getRelations(): Collection
    {
        return $this->relations;
    }

    public function addRelation(Relation $relation): static
    {
        if (!$this->relations->contains($relation)) {
            $this->relations->add($relation);
            $relation->setPersonA($this);
        }

        return $this;
    }

    public function removeRelation(Relation $relation): static
    {
        if ($this->relations->removeElement($relation)) {
            // set the owning side to null (unless already changed)
            if ($relation->getPersonA() === $this) {
                $relation->setPersonA(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PrivateConversation>
     */
    public function getPrivateConversations(): Collection
    {
        return $this->privateConversations;
    }

    public function addPrivateConversation(PrivateConversation $privateConversation): static
    {
        if (!$this->privateConversations->contains($privateConversation)) {
            $this->privateConversations->add($privateConversation);
            $privateConversation->setIndividualA($this);
        }

        return $this;
    }

    public function removePrivateConversation(PrivateConversation $privateConversation): static
    {
        if ($this->privateConversations->removeElement($privateConversation)) {
            // set the owning side to null (unless already changed)
            if ($privateConversation->getIndividualA() === $this) {
                $privateConversation->setIndividualA(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PrivateMessage>
     */
    public function getPrivateMessages(): Collection
    {
        return $this->privateMessages;
    }

    public function addPrivateMessage(PrivateMessage $privateMessage): static
    {
        if (!$this->privateMessages->contains($privateMessage)) {
            $this->privateMessages->add($privateMessage);
            $privateMessage->setAuthor($this);
        }

        return $this;
    }

    public function removePrivateMessage(PrivateMessage $privateMessage): static
    {
        if ($this->privateMessages->removeElement($privateMessage)) {
            // set the owning side to null (unless already changed)
            if ($privateMessage->getAuthor() === $this) {
                $privateMessage->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PrivateResponse>
     */
    public function getPrivateResponses(): Collection
    {
        return $this->privateResponses;
    }

    public function addPrivateResponse(PrivateResponse $privateResponse): static
    {
        if (!$this->privateResponses->contains($privateResponse)) {
            $this->privateResponses->add($privateResponse);
            $privateResponse->setAuthor($this);
        }

        return $this;
    }

    public function removePrivateResponse(PrivateResponse $privateResponse): static
    {
        if ($this->privateResponses->removeElement($privateResponse)) {
            // set the owning side to null (unless already changed)
            if ($privateResponse->getAuthor() === $this) {
                $privateResponse->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, GroupConversation>
     */
    public function getGroupConversations(): Collection
    {
        return $this->groupConversations;
    }

    public function addGroupConversation(GroupConversation $groupConversation): static
    {
        if (!$this->groupConversations->contains($groupConversation)) {
            $this->groupConversations->add($groupConversation);
            $groupConversation->addMember($this);
        }

        return $this;
    }

    public function removeGroupConversation(GroupConversation $groupConversation): static
    {
        if ($this->groupConversations->removeElement($groupConversation)) {
            $groupConversation->removeMember($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, GroupMessage>
     */
    public function getGroupMessages(): Collection
    {
        return $this->groupMessages;
    }

    public function addGroupMessage(GroupMessage $groupMessage): static
    {
        if (!$this->groupMessages->contains($groupMessage)) {
            $this->groupMessages->add($groupMessage);
            $groupMessage->setAuthor($this);
        }

        return $this;
    }

    public function removeGroupMessage(GroupMessage $groupMessage): static
    {
        if ($this->groupMessages->removeElement($groupMessage)) {
            // set the owning side to null (unless already changed)
            if ($groupMessage->getAuthor() === $this) {
                $groupMessage->setAuthor(null);
            }
        }

        return $this;
    }
}
