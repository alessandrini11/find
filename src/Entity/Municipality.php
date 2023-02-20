<?php

namespace App\Entity;

use App\Repository\MunicipalityRepository;
use App\Trait\DateTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: MunicipalityRepository::class)]
#[UniqueEntity(fields: ['name'], message: 'Cette villes existe deja')]
#[ORM\HasLifecycleCallbacks]
class Municipality
{
    use DateTrait;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'municipalities')]
    private ?Town $town = null;

    #[ORM\OneToMany(mappedBy: 'municipality', targetEntity: Declaration::class)]
    private Collection $declarations;

    public function __construct()
    {
        $this->declarations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getTown(): ?Town
    {
        return $this->town;
    }

    public function setTown(?Town $town): self
    {
        $this->town = $town;

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
            $declaration->setMunicipality($this);
        }

        return $this;
    }

    public function removeDeclaration(Declaration $declaration): self
    {
        if ($this->declarations->removeElement($declaration)) {
            // set the owning side to null (unless already changed)
            if ($declaration->getMunicipality() === $this) {
                $declaration->setMunicipality(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
