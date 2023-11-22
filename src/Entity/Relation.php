<?php

namespace App\Entity;

use App\Repository\RelationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: RelationRepository::class)]
class Relation
{
    #[Groups('profile')]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'relations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Profile $personA = null;

    #[ORM\ManyToOne(inversedBy: 'relations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Profile $personB = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPersonA(): ?Profile
    {
        return $this->personA;
    }

    public function setPersonA(?Profile $personA): static
    {
        $this->personA = $personA;

        return $this;
    }

    public function getPersonB(): ?Profile
    {
        return $this->personB;
    }

    public function setPersonB(?Profile $personB): static
    {
        $this->personB = $personB;

        return $this;
    }
}
