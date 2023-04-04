<?php

namespace App\Entity;

use App\Repository\DocumentRepository;
use App\Trait\DateTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: DocumentRepository::class)]
#[UniqueEntity(fields: ['idNumber'], message: 'Ce document existe déjà')]
#[ORM\HasLifecycleCallbacks]
class Document
{
    use DateTrait;

    const DIRECTORY = 'documents';
    const NIC = 'cni';
    const BIRTH_CERTIFICATE = 'acte naissance';
    const DEATH_CERTIFICATE = 'acte décès';
    const MARRIAGE_CERTIFICATE = 'acte de mariage';
    const CERTIFICATE = 'diplôme';

    const TYPES = [
        self::NIC => 'cni',
        self::BIRTH_CERTIFICATE => 'acte naissance',
        self::DEATH_CERTIFICATE => 'acte décès',
        self::MARRIAGE_CERTIFICATE => 'acte de mariage',
        self::CERTIFICATE => 'diplôme'
    ];
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $owner = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column(length: 255)]
    private ?string $idNumber = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'documents')]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'document', targetEntity: Declaration::class, cascade: ["persist"])]
    private Collection $declarations;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    private ?string $imageFile;

    public function __construct()
    {
        $this->declarations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOwner(): ?string
    {
        return $this->owner;
    }

    public function setOwner(string $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getIdNumber(): ?string
    {
        return $this->idNumber;
    }

    public function setIdNumber(string $idNumber): self
    {
        $this->idNumber = $idNumber;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

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
            $declaration->setDocument($this);
        }

        return $this;
    }

    public function removeDeclaration(Declaration $declaration): self
    {
        if ($this->declarations->removeElement($declaration)) {
            // set the owning side to null (unless already changed)
            if ($declaration->getDocument() === $this) {
                $declaration->setDocument(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->type ." - ". $this->getUser()->getFirstname(). " ". $this->getUser()->getLastname();
    }

    public function getLabel(): ?string
    {
        return $this->getType(). " - ". $this->getOwner();
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getImageFile(): ?string
    {
        return $this->imageFile;
    }

    /**
     * @param string|null $imageFile
     */
    public function setImageFile(?string $imageFile): void
    {
        $this->imageFile = $imageFile;
    }
}
