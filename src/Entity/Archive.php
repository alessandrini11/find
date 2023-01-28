<?php

namespace App\Entity;

use App\Repository\ArchiveRepository;
use App\Trait\DateTrait;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArchiveRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Archive
{
    use DateTrait;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'archives')]
    private ?Declaration $declaration = null;

    #[ORM\ManyToOne(inversedBy: 'archives')]
    private ?User $owner = null;

    #[ORM\ManyToOne(inversedBy: 'actors')]
    private ?User $actor = null;

    #[ORM\Column]
    private ?bool $ownerValidation = null;

    #[ORM\Column]
    private ?bool $actorValidation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDeclaration(): ?Declaration
    {
        return $this->declaration;
    }

    public function setDeclaration(?Declaration $declaration): self
    {
        $this->declaration = $declaration;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function getActor(): ?User
    {
        return $this->actor;
    }

    public function setActor(?User $actor): self
    {
        $this->actor = $actor;

        return $this;
    }

    public function isOwnerValidation(): ?bool
    {
        return $this->ownerValidation;
    }

    public function setOwnerValidation(bool $ownerValidation): self
    {
        $this->ownerValidation = $ownerValidation;

        return $this;
    }

    public function isActorValidation(): ?bool
    {
        return $this->actorValidation;
    }

    public function setActorValidation(bool $actorValidation): self
    {
        $this->actorValidation = $actorValidation;

        return $this;
    }
}
