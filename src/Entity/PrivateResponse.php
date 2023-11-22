<?php

namespace App\Entity;

use App\Repository\PrivateResponseRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PrivateResponseRepository::class)]
class PrivateResponse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['message_private_conversation'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['message_private_conversation'])]
    private ?string $content = null;

    #[ORM\ManyToOne(inversedBy: 'privateResponses')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['message_private_conversation'])]
    private ?Profile $author = null;

    #[ORM\ManyToOne(inversedBy: 'privateResponses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?PrivateMessage $privateMessage = null;

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

    public function getPrivateMessage(): ?PrivateMessage
    {
        return $this->privateMessage;
    }

    public function setPrivateMessage(?PrivateMessage $privateMessage): static
    {
        $this->privateMessage = $privateMessage;

        return $this;
    }
}
