<?php

namespace App\Controller;

use App\Entity\Declaration;
use App\Entity\Transaction;
use App\Exceptions\Api\UnauthorizedException;
use App\Exceptions\Api\NotFoundException;
use App\Form\DeclarationSearchType;
use App\Models\DeclarationSearch;
use App\Repository\DeclarationRepository;
use App\Repository\FundRepository;
use App\Repository\MunicipalityRepository;
use App\Repository\TransactionRepository;
use App\Repository\UserRepository;
use App\Service\TransactionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use App\DTO\Response as ApiResponse;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home', methods: ['GET', 'POST'])]
    public function index(MailerInterface $mailer, Request $request, DeclarationRepository $declarationRepository): Response
    {
        $declarationSearch = new DeclarationSearch();
        $form = $this->createForm(DeclarationSearchType::class, $declarationSearch);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $declarations = $declarationRepository->searchDeclaration($declarationSearch);
           return $this->render('home/search.html.twig', [
                "declarations" => $declarations,
                "form" => $form->createView()
            ]);
        }
        return $this->render('home/index.html.twig', [
            "form" => $form->createView()
        ]);
    }

    #[Route('/a-propos', name: 'app_about', methods: 'GET')]
    public function about(): Response
    {
        return $this->render('home/about.html.twig');
    }

    #[Route(
        '/register/confirmation-mail-sent-this-is-a-generated-page-not-to-visit',
        name: 'app_confirmation_mail',
        methods: ['POST', 'GET'])
    ]
    public function confirmationMail(): Response
    {
       return $this->render('registration/mailsent.html.twig');
    }

    #[Route('/mentions-legales', name: 'app_mentions', methods: 'GET')]
    public function mentions(): Response
    {
        return $this->render('home/mentions.html.twig');
    }

    #[Route('/contact', name: 'app_contact', methods: 'GET')]
    public function contact(): Response
    {
        return $this->render('home/contact.html.twig');
    }

    #[Route('/recherche', name: 'app_search', methods: ['GET', 'POST'])]
    public function search(DeclarationRepository $declarationRepository, Request $request): Response
    {
        $declarations = $declarationRepository->findBy(["completed" => true]);
        $declarationSearch = new DeclarationSearch();
        $form = $this->createForm(DeclarationSearchType::class, $declarationSearch);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $declarations = $declarationRepository->searchDeclaration($declarationSearch);
            return $this->render('home/search.html.twig', [
                "declarations" => $declarations,
                "form" => $form->createView()
            ]);
        }
        return $this->render('home/search.html.twig', [
            "declarations" => $declarations,
            "form" => $form->createView()
        ]);
    }



    #[Route('/declaration/{id}', name: 'app_show_declaration', methods: 'GET')]
    public function showDeclaration(
        Declaration $declaration,
        TransactionRepository $transactionRepository,
        FundRepository $fundRepository
    ): Response
    {
        $user = $this->getUser();
        $isPayed = false;
        if($user){
            $fund = $fundRepository->findOneBy(["user" => $user]);
            $transaction = $transactionRepository->findOneBy([
                "fund" => $fund,
                "motif" => Transaction::DEPOSIT_FOR_PAYMENT.' '.$declaration->getLabel()
            ]);
            if($transaction){
                $isPayed = true;
            }
        }

        return $this->render('home/show.html.twig', [
            "declaration" => $declaration,
            "user" => $user,
            "isPayed" => $isPayed
        ]);
    }

    #[Route('/add-payment/{id}', name: 'app_is_auth', methods: 'POST')]
    public function addPayment(
        int $id,
        DeclarationRepository $declarationRepository,
        FundRepository $fundRepository,
        TransactionService $transactionService
    ): JsonResponse
    {
        $user = $this->getUser();
        if(!$user){
            throw new UnauthorizedException();
        }
        $declaration = $declarationRepository->find($id);
        if(!$declaration){
            throw new NotFoundException();
        }
        $fund = $fundRepository->findOneBy(["user" => $user]);
        $transactionService->create(
            $fund,
            500,
            Transaction::DEPOSIT,
            Transaction::DEPOSIT_FOR_PAYMENT .' '. $declaration->getLabel()
        );
        $response = new ApiResponse();
        return $this->json($response);
    }
}
