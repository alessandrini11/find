<?php

namespace App\Entity;

use App\Repository\DeclarationRepository;
use App\Trait\DateTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DeclarationRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Declaration
{
    use DateTrait;

    const LOST = 'perdu';
    const FOUND = 'trouvé';

    const STATUS = [
        self::LOST => 'perdu',
        self::FOUND => 'trouvé',
    ];
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'declarations')]
    private ?Document $document = null;

    #[ORM\ManyToOne(inversedBy: 'declarations')]
    private ?User $user = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    private ?int $reward = 0;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\OneToMany(mappedBy: 'declaration', targetEntity: Archive::class)]
    private Collection $archives;

    #[ORM\ManyToMany(targetEntity: Visitor::class, mappedBy: 'declarations')]
    private Collection $visitors;

    public function __construct()
    {
        $this->archives = new ArrayCollection();
        $this->visitors = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDocument(): ?Document
    {
        return $this->document;
    }

    public function setDocument(?Document $document): self
    {
        $this->document = $document;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getReward(): ?int
    {
        return $this->reward;
    }

    public function setReward(?int $reward): self
    {
        $this->reward = $reward;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection<int, Archive>
     */
    public function getArchives(): Collection
    {
        return $this->archives;
    }

    public function addArchive(Archive $archive): self
    {
        if (!$this->archives->contains($archive)) {
            $this->archives->add($archive);
            $archive->setDeclaration($this);
        }

        return $this;
    }

    public function removeArchive(Archive $archive): self
    {
        if ($this->archives->removeElement($archive)) {
            // set the owning side to null (unless already changed)
            if ($archive->getDeclaration() === $this) {
                $archive->setDeclaration(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Visitor>
     */
    public function getVisitors(): Collection
    {
        return $this->visitors;
    }

    public function addVisitor(Visitor $visitor): self
    {
        if (!$this->visitors->contains($visitor)) {
            $this->visitors->add($visitor);
            $visitor->addDeclaration($this);
        }

        return $this;
    }

    public function removeVisitor(Visitor $visitor): self
    {
        if ($this->visitors->removeElement($visitor)) {
            $visitor->removeDeclaration($this);
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->getDocument();
    }
}
