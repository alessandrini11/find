<?php

namespace App\Entity;

use App\Repository\VisitorRepository;
use App\Trait\DateTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VisitorRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Visitor
{
    use DateTrait;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $ip = null;

    #[ORM\ManyToMany(targetEntity: Declaration::class, inversedBy: 'visitors')]
    private Collection $declarations;

    public function __construct()
    {
        $this->declarations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIp(): ?string
    {
        return $this->ip;
    }

    public function setIp(string $ip): self
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * @return Collection<int, Declaration>
     */
    public function getDeclarations(): Collection
    {
        return $this->declarations;
    }

    public function addDeclaration(Declaration $declaration): self
    {
        if (!$this->declarations->contains($declaration)) {
            $this->declarations->add($declaration);
        }

        return $this;
    }

    public function removeDeclaration(Declaration $declaration): self
    {
        $this->declarations->removeElement($declaration);

        return $this;
    }
}
