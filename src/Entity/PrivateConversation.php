<?php

namespace App\Entity;

use App\Repository\PrivateConversationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PrivateConversationRepository::class)]
class PrivateConversation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['privateConversation:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'privateConversations')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['privateConversation:read'])]
    private ?Profile $individualA = null;

    #[ORM\ManyToOne(inversedBy: 'privateConversations')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['privateConversation:read'])]
    private ?Profile $individualB = null;

    #[ORM\OneToMany(mappedBy: 'PrivateConversation', targetEntity: PrivateMessage::class, orphanRemoval: true)]
    private Collection $privateMessages;

    public function __construct()
    {
        $this->privateMessages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIndividualA(): ?Profile
    {
        return $this->individualA;
    }

    public function setIndividualA(?Profile $individualA): static
    {
        $this->individualA = $individualA;

        return $this;
    }

    public function getIndividualB(): ?Profile
    {
        return $this->individualB;
    }

    public function setIndividualB(?Profile $individualB): static
    {
        $this->individualB = $individualB;

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
            $privateMessage->setPrivateConversation($this);
        }

        return $this;
    }

    public function removePrivateMessage(PrivateMessage $privateMessage): static
    {
        if ($this->privateMessages->removeElement($privateMessage)) {
            // set the owning side to null (unless already changed)
            if ($privateMessage->getPrivateConversation() === $this) {
                $privateMessage->setPrivateConversation(null);
            }
        }

        return $this;
    }
}
