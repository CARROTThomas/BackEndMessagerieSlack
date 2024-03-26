<?php

namespace App\Entity;

use App\Repository\PrivateMessageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;

#[ORM\Entity(repositoryClass: PrivateMessageRepository::class)]
class PrivateMessage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['message_private_conversation'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['message_private_conversation'])]
    private ?string $content = null;

    #[ORM\ManyToOne(inversedBy: 'privateMessages')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['message_private_conversation'])]
    private ?Profile $author = null;

    #[ORM\ManyToOne(inversedBy: 'privateMessages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?PrivateConversation $PrivateConversation = null;

    #[ORM\OneToMany(mappedBy: 'privateMessage', targetEntity: PrivateResponse::class, orphanRemoval: true)]
    #[Groups(['message_private_conversation'])]
    private Collection $privateResponses;

    #[ORM\OneToMany(mappedBy: 'privateMessage', targetEntity: Image::class)]
    #[Groups(['message_private_conversation'])]
    private Collection $images;

    #[SerializedName("images")]
    #[Groups(['message_private_conversation'])]
    private ArrayCollection $imagesUrls;

    private ?Array $associatedImages = null;


    public function __construct()
    {
        $this->privateResponses = new ArrayCollection();
        $this->images = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): static
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

    public function getPrivateConversation(): ?PrivateConversation
    {
        return $this->PrivateConversation;
    }

    public function setPrivateConversation(?PrivateConversation $PrivateConversation): static
    {
        $this->PrivateConversation = $PrivateConversation;

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
            $privateResponse->setPrivateMessage($this);
        }

        return $this;
    }

    public function removePrivateResponse(PrivateResponse $privateResponse): static
    {
        if ($this->privateResponses->removeElement($privateResponse)) {
            // set the owning side to null (unless already changed)
            if ($privateResponse->getPrivateMessage() === $this) {
                $privateResponse->setPrivateMessage(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Image>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): static
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setPrivateMessage($this);
        }

        return $this;
    }

    public function removeImage(Image $image): static
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getPrivateMessage() === $this) {
                $image->setPrivateMessage(null);
            }
        }

        return $this;
    }

    public function getAssociatedImages(): ?array
    {
        return $this->associatedImages;
    }

    public function setAssociatedImages(?array $associatedImages): void
    {
        $this->associatedImages = $associatedImages;
    }

    public function getImagesUrls(): ArrayCollection
    {
        return $this->imagesUrls;
    }

    public function setImagesUrls(ArrayCollection $imagesUrls): void
    {
        $this->imagesUrls = $imagesUrls;
    }

}
