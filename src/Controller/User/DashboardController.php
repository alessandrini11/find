<?php

namespace App\Controller\User;

use App\Repository\ArchiveRepository;
use App\Repository\DeclarationRepository;
use App\Repository\DocumentRepository;
use App\Repository\TransactionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(
        ArchiveRepository $archiveRepository,
        DeclarationRepository $declarationRepository,
        TransactionRepository $transactionRepository,
        DocumentRepository $documentRepository,

    ): Response
    {
        return $this->render('dashboard/index.html.twig', [
            'founded_docs' => $archiveRepository->findAll(),
            'declaration' => $declarationRepository->findAll(),
            'transactions' => $transactionRepository->findAll(),
            'cagnote' => 58413,
            'documents' => $documentRepository->findAll(),
            'page_name' => 'dashboard',
            'page_route' => 'app_dashboard'
        ]);
    }
}
