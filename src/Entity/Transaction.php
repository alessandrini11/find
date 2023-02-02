<?php

namespace App\Entity;

use App\Repository\TransactionRepository;
use App\Trait\DateTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TransactionRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Transaction
{
    use DateTrait;

    const DEPOSIT = 'deposit';
    const WITHDRAWAL = 'withdrawal';
    const TRANSFER = 'transfer';

    const TYPES = [
        self::DEPOSIT => 'dépot',
        self::WITHDRAWAL => 'retrait',
        self::TRANSFER => 'transfert'
    ];

    const TRANSFER_FROM_ARCHIVES = 'archive de';
    const WITHDRAW_FROM_ACCOUNT = 'retrait de la cagnote';
    const DEPOSIT_FOR_PAYMENT = 'payement pour';


    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column]
    private ?int $montant = null;

    #[ORM\ManyToOne(inversedBy: 'transactions')]
    private ?Fund $fund = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $motif = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getMontant(): ?int
    {
        return $this->montant;
    }

    public function setMontant(int $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getFund(): ?Fund
    {
        return $this->fund;
    }

    public function setFund(?Fund $fund): self
    {
        $this->fund = $fund;

        return $this;
    }

    public function getMotif(): ?string
    {
        return $this->motif;
    }

    public function setMotif(string $motif): self
    {
        $this->motif = $motif;

        return $this;
    }
}
