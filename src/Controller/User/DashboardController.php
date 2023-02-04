<?php

namespace App\Controller\User;

use App\Form\ChangePasswordFormType;
use App\Form\UserType;
use App\Repository\FundRepository;
use App\Repository\UserRepository;
use App\Service\AuthService;
use App\Service\TransactionService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/dashboard')]
class DashboardController extends AbstractController
{
    #[Route('/', name: 'app_dashboard')]
    public function index(
        UserRepository $userRepository,
        TransactionService $transactionService,
        FundRepository $fundRepository
    ): Response
    {
        $user = $userRepository->find(3);
        $documents = $user->getDocuments();
        $declarations = $user->getDeclarations();
        $archives = $user->getArchives();
        $fund = $fundRepository->findOneBy(["user" => $user]);
        $transactions = $fund->getTransactions();
        $balance = $transactionService->getBalance($fund);

        return $this->render('dashboard/index.html.twig', [
            'founded_docs' => $archives,
            'declaration' => $declarations,
            'transactions' => $transactions,
            'documents' => $documents,
            'balance' => $balance,
            'page_name' => 'dashboard',
            'page_route' => 'app_dashboard'
        ]);
    }


    #[Route('/profile', name: 'app_dashboard_profile', methods: ['GET', 'POST'])]
    public function profile(UserRepository $userRepository, Request $request, AuthService $authService): Response
    {
        $user = $userRepository->find(2);
        $updatePasswordForm = $this->createForm(ChangePasswordFormType::class, $user);
        $updatePasswordForm->handleRequest($request);
        $authService->updatePassword($updatePasswordForm, $user);

        $updateUserInfos = $this->createForm(UserType::class, $user);
        $updateUserInfos->handleRequest($request);
        $authService->updateUserInfos($updateUserInfos, $user);
        return $this->render('dashboard/profile.html.twig', [
            'change_password_form' => $updatePasswordForm->createView(),
            'change_user_info_form' => $updateUserInfos->createView(),
            'user' => $user
        ]);
    }
}
