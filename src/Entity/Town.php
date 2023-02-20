<?php

namespace App\Entity;

use App\Repository\TownRepository;
use App\Trait\DateTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: TownRepository::class)]
#[UniqueEntity(fields: ['name'], message: 'Ce quartier existe deja')]
#[ORM\HasLifecycleCallbacks]

class Town
{
    use DateTrait;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'town', targetEntity: Municipality::class)]
    private Collection $municipalities;

    public function __construct()
    {
        $this->municipalities = new ArrayCollection();
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

    /**
     * @return Collection<int, Municipality>
     */
    public function getMunicipalities(): Collection
    {
        return $this->municipalities;
    }

    public function addMunicipality(Municipality $municipality): self
    {
        if (!$this->municipalities->contains($municipality)) {
            $this->municipalities->add($municipality);
            $municipality->setTown($this);
        }

        return $this;
    }

    public function removeMunicipality(Municipality $municipality): self
    {
        if ($this->municipalities->removeElement($municipality)) {
            // set the owning side to null (unless already changed)
            if ($municipality->getTown() === $this) {
                $municipality->setTown(null);
            }
        }

        return $this;
    }
    public function __toString(): string
    {
        return $this->name;
    }
}
