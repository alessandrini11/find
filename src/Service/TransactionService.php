<?php

namespace App\Service;

use App\Entity\Fund;
use App\Entity\Transaction;
use App\Repository\TransactionRepository;

class TransactionService
{
    private TransactionRepository $transactionRepository;

    public function __construct(TransactionRepository $transactionRepository)
    {
        $this->transactionRepository = $transactionRepository;
    }

    public function create(Fund $fund, int $amount, string $type, string $reason): void
    {
        $transaction = new Transaction();
        $transaction->setFund($fund)
            ->setType($type)
            ->setMontant($amount)
            ->setMotif($reason);

        $this->transactionRepository->save($transaction, true);
    }
}