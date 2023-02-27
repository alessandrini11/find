<?php

namespace App\Entity;

use App\Repository\UserRepository;
use App\Trait\DateTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use DateTrait;
    const ROLE_USER = 'ROLE_USER';
    const ROLE_ADMIN = 'ROLE_ADMIN';
    // const ROLE_SUPER_ADMIN = 'ROLE_SUPER_ADMIN';

    const ROLES = [
        self::ROLE_ADMIN => "Admin",
        self::ROLE_USER => "User",
        // self::ROLE_SUPER_ADMIN => "Super admin"
    ];

    const MAN = 'man';
    const WOMAN = 'woman';

    const sexs = [
        self::MAN => 'homme',
        self::WOMAN => 'femme',
    ];
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column(nullable: true)]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    private ?string $lastname = null;

    #[ORM\Column(length: 255)]
    private ?string $sex = null;

    #[ORM\Column(nullable: true)]
    private ?string $telephone = null;

    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;

    private $plainpassword = null;

    private ?string $actualPassword = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Document::class, cascade: ["remove"])]
    private Collection $documents;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Payement::class, cascade: ["remove"])]
    private Collection $payements;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Declaration::class, cascade: ["remove"])]
    private Collection $declarations;

    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: Archive::class, cascade: ["remove"])]
    private Collection $archives;

    #[ORM\OneToMany(mappedBy: 'actor', targetEntity: Archive::class, cascade: ["remove"])]
    private Collection $actors;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Fund::class, cascade: ["remove", "persist"] )]
    #[ORM\JoinColumn()]
    private Collection $fund;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $googleId = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $hostedDomain = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Comment::class, cascade: ["remove"])]
    private Collection $comments;


    public function __construct()
    {
        $this->documents = new ArrayCollection();
        $this->payements = new ArrayCollection();
        $this->declarations = new ArrayCollection();
        $this->archives = new ArrayCollection();
        $this->actors = new ArrayCollection();
        $this->fund = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getSex(): ?string
    {
        return $this->sex;
    }

    public function setSex(string $sex): self
    {
        $this->sex = $sex;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    /**
     * Get the value of plainpassword
     */ 
    public function getPlainpassword()
    {
        return $this->plainpassword;
    }

    /**
     * Set the value of plainpassword
     *
     * @return  self
     */ 
    public function setPlainpassword($plainpassword)
    {
        $this->plainpassword = $plainpassword;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getActualPassword(): ?string
    {
        return $this->actualPassword;
    }

    /**
     * @param string|null $actualPassword
     */
    public function setActualPassword(?string $actualPassword): void
    {
        $this->actualPassword = $actualPassword;
    }

    /**
     * @return Collection<int, Document>
     */
    public function getDocuments(): Collection
    {
        return $this->documents;
    }

    public function addDocument(Document $document): self
    {
        if (!$this->documents->contains($document)) {
            $this->documents->add($document);
            $document->setUser($this);
        }

        return $this;
    }

    public function removeDocument(Document $document): self
    {
        if ($this->documents->removeElement($document)) {
            // set the owning side to null (unless already changed)
            if ($document->getUser() === $this) {
                $document->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Payement>
     */
    public function getPayements(): Collection
    {
        return $this->payements;
    }

    public function addPayement(Payement $payement): self
    {
        if (!$this->payements->contains($payement)) {
            $this->payements->add($payement);
            $payement->setUser($this);
        }

        return $this;
    }

    public function removePayement(Payement $payement): self
    {
        if ($this->payements->removeElement($payement)) {
            // set the owning side to null (unless already changed)
            if ($payement->getUser() === $this) {
                $payement->setUser(null);
            }
        }

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
            $declaration->setUser($this);
        }

        return $this;
    }

    public function removeDeclaration(Declaration $declaration): self
    {
        if ($this->declarations->removeElement($declaration)) {
            // set the owning side to null (unless already changed)
            if ($declaration->getUser() === $this) {
                $declaration->setUser(null);
            }
        }

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
            $archive->setOwner($this);
        }

        return $this;
    }

    public function removeArchive(Archive $archive): self
    {
        if ($this->archives->removeElement($archive)) {
            // set the owning side to null (unless already changed)
            if ($archive->getOwner() === $this) {
                $archive->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Archive>
     */
    public function getActors(): Collection
    {
        return $this->actors;
    }

    public function addActor(Archive $actor): self
    {
        if (!$this->actors->contains($actor)) {
            $this->actors->add($actor);
            $actor->setActor($this);
        }

        return $this;
    }

    public function removeActor(Archive $actor): self
    {
        if ($this->actors->removeElement($actor)) {
            // set the owning side to null (unless already changed)
            if ($actor->getActor() === $this) {
                $actor->setActor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Fund>
     */
    public function getFund(): Collection
    {
        return $this->fund;
    }

    public function addFund(Fund $fund): self
    {
        if (!$this->fund->contains($fund)) {
            $this->fund->add($fund);
            $fund->setUser($this);
        }

        return $this;
    }

    public function removeFund(Fund $fund): self
    {
        if ($this->fund->removeElement($fund)) {
            // set the owning side to null (unless already changed)
            if ($fund->getUser() === $this) {
                $fund->setUser(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->firstname. " ". $this->lastname;
    }

    public function getGoogleId(): ?string
    {
        return $this->googleId;
    }

    public function setGoogleId(?string $googleId): self
    {
        $this->googleId = $googleId;

        return $this;
    }

    public function getHostedDomain(): ?string
    {
        return $this->hostedDomain;
    }

    public function setHostedDomain(?string $hostedDomain): self
    {
        $this->hostedDomain = $hostedDomain;

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setUser($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getUser() === $this) {
                $comment->setUser(null);
            }
        }

        return $this;
    }
}
