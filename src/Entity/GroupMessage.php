<?php

namespace App\Entity;

use App\Repository\GroupMessageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: GroupMessageRepository::class)]
class GroupMessage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('message_group_conversation')]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups('message_group_conversation')]
    private ?string $content = null;

    #[ORM\ManyToOne(inversedBy: 'groupMessages')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups('message_group_conversation')]
    private ?Profile $author = null;

    #[ORM\ManyToOne(inversedBy: 'groupMessages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?GroupConversation $groupConversation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getAuthor(): ?Profile
    {
        return $this->author;
    }

    public function setAuthor(?Profile $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function getGroupConversation(): ?GroupConversation
    {
        return $this->groupConversation;
    }

    public function setGroupConversation(?GroupConversation $groupConversation): static
    {
        $this->groupConversation = $groupConversation;

        return $this;
    }
}
