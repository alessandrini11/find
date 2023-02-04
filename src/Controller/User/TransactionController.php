<?php

namespace App\Controller\User;

use App\Repository\FundRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/dashboard/transaction')]
class TransactionController extends AbstractController
{
    #[Route('/', name: 'app_transaction_index', methods: ['GET'])]
    public function index(FundRepository $fundRepository, UserRepository $userRepository): Response
    {
        $user = $userRepository->find(3);
        $fund = $fundRepository->findOneBy(["user" => $user]);
        $transactions = $fund->getTransactions();
        return $this->render('transaction/index.html.twig', [
            'transactions' => $transactions,
        ]);
    }

}
