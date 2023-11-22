<?php

namespace App\Entity;

use App\Repository\GroupConversationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: GroupConversationRepository::class)]
class GroupConversation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('groupConversation')]
    private ?int $id = null;

    #[ORM\ManyToMany(targetEntity: Profile::class, inversedBy: 'groupConversations')]
    #[Groups('groupConversation')]
    private Collection $members;

    #[ORM\ManyToOne(inversedBy: 'groupConversations')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups('groupConversation')]
    private ?Profile $owner = null;

    #[ORM\OneToMany(mappedBy: 'groupConversation', targetEntity: GroupMessage::class, orphanRemoval: true)]
    #[Groups('message_group_conversation')]
    private Collection $groupMessages;

    public function __construct()
    {
        $this->members = new ArrayCollection();
        $this->groupMessages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Profile>
     */
    public function getMembers(): Collection
    {
        return $this->members;
    }

    public function addMember(Profile $member): static
    {
        if (!$this->members->contains($member)) {
            $this->members->add($member);
        }

        return $this;
    }

    public function removeMember(Profile $member): static
    {
        $this->members->removeElement($member);

        return $this;
    }

    public function getOwner(): ?Profile
    {
        return $this->owner;
    }

    public function setOwner(?Profile $owner): static
    {
        $this->owner = $owner;

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
            $groupMessage->setGroupConversation($this);
        }

        return $this;
    }

    public function removeGroupMessage(GroupMessage $groupMessage): static
    {
        if ($this->groupMessages->removeElement($groupMessage)) {
            // set the owning side to null (unless already changed)
            if ($groupMessage->getGroupConversation() === $this) {
                $groupMessage->setGroupConversation(null);
            }
        }

        return $this;
    }
}
