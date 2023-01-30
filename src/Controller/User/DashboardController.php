<?php

namespace App\Controller\User;

use App\Repository\ArchiveRepository;
use App\Repository\DeclarationRepository;
use App\Repository\DocumentRepository;
use App\Repository\TransactionRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(
        UserRepository $userRepository
    ): Response
    {
        $user = $userRepository->find(3);
        $documents = $user->getDocuments();
        $declarations = $user->getDeclarations();
        $archives = $user->getArchives();
        $transactions = [];
        foreach ($user->getFund() as $key => $fund)
        {
            if($key === 0)
            {
                foreach ($fund->getTransactions() as $transaction)
                {
                    $transactions[] = $transaction;
                }
            }
        }

        return $this->render('dashboard/index.html.twig', [
            'founded_docs' => $archives,
            'declaration' => $declarations,
            'transactions' => $transactions,
            'documents' => $documents,
            'cagnote' => 58413,
            'page_name' => 'dashboard',
            'page_route' => 'app_dashboard'
        ]);
    }
}
