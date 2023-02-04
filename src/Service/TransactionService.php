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

    public function getBalance(Fund $fund): int
    {
       $transactionsTransfer = $this->transactionRepository->findBy(["fund" => $fund, "type" => Transaction::TRANSFER]);
       $transactionsWithdraw = $this->transactionRepository->findBy(["fund" => $fund, "type" => Transaction::WITHDRAWAL]);

       $totalTransferAmount = $this->getAmount($transactionsTransfer);
       $totalWithdrawAmount = $this->getAmount($transactionsWithdraw);

       return $totalTransferAmount - $totalWithdrawAmount;
    }

    /**
     * @param Transaction[] $transactions
     * @return int
     */
    private function getAmount(array $transactions): int
    {
        $amount = 0;
        foreach ($transactions as $transaction)
        {
            $amount = $amount + $transaction->getMontant();
        }
        return $amount;
    }


}