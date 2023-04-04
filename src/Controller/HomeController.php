<?php

namespace App\Controller;

use App\DTO\SmsResponse;
use App\Entity\Comment;
use App\Entity\Declaration;
use App\Entity\Transaction;
use App\Exceptions\Api\UnauthorizedException;
use App\Exceptions\Api\NotFoundException;
use App\Form\CommentType;
use App\Form\DeclarationSearchType;
use App\Models\DeclarationSearch;
use App\Repository\CommentRepository;
use App\Repository\DeclarationRepository;
use App\Repository\FundRepository;
use App\Repository\TransactionRepository;
use App\Service\CommentService;
use App\Service\MessageBirdService;
use App\Service\SmsService;
use App\Service\TransactionService;
use App\Service\VisitorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\DTO\Response as ApiResponse;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home', methods: ['GET', 'POST'])]
    public function index(
        VisitorService $visitorService,
        Request $request,
        DeclarationRepository $declarationRepository,
        CommentRepository $commentRepository,
    ): Response
    {
        $ip = $request->getClientIp();
        $visitorService->getByIp($ip);
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
            "form" => $form->createView(),
            "comments" => $commentRepository->findBy(["status" => true], ["createdAt" => "DESC"],10)
        ]);
    }

    #[Route('/a-propos', name: 'app_about', methods: 'GET')]
    public function about(Request $request, VisitorService $visitorService): Response
    {
        $ip = $request->getClientIp();
        $visitorService->getByIp($ip);
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
    public function mentions(Request $request, VisitorService $visitorService): Response
    {
        $ip = $request->getClientIp();
        $visitorService->getByIp($ip);
        return $this->render('home/mentions.html.twig');
    }

    #[Route('/contact', name: 'app_contact', methods: 'GET')]
    public function contact(Request $request, VisitorService $visitorService): Response
    {
        $ip = $request->getClientIp();
        $visitorService->getByIp($ip);
        return $this->render('home/contact.html.twig');
    }

    #[Route('/recherche', name: 'app_search', methods: ['GET', 'POST'])]
    public function search(DeclarationRepository $declarationRepository, Request $request, VisitorService $visitorService): Response
    {
        $ip = $request->getClientIp();
        $visitorService->getByIp($ip);
        $declarations = $declarationRepository->findBy(["completed" => false]);
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
        FundRepository $fundRepository,
        VisitorService $visitorService,
        Request $request
    ): Response
    {
        $ip = $request->getClientIp();
        $visitorService->isDeclarationInVisitor($ip, $declaration);
        $user = $this->getUser();
        $isPayed = false;
        if($declaration->isCompleted()){
            throw new BadRequestException("Bad Request");
        }
        if($declaration->getStatus() === Declaration::LOST)
        {
            $isPayed = true;
        }
        if($user){
            $fund = $fundRepository->findBy(["user" => $user]);
            $transaction = $transactionRepository->findOneBy([
                "fund" => $fund,
                "motif" => Transaction::DEPOSIT_FOR_PAYMENT .' '. $declaration->getLabel()
            ]);
            if($transaction){
                $isPayed = true;
            }
        }
        // dd($isPayed);
        return $this->render('home/show.html.twig', [
            "declaration" => $declaration,
            "user" => $user,
            "isPayed" => $isPayed
        ]);
    }

    #[Route('/avis', name: 'app_comment', methods: ['GET', 'POST'])]
    public function comment(CommentRepository $commentRepository, Request $request, CommentService $commentService): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        if($this->getUser()){
            if($form->isSubmitted() && $form->isValid()){
                $comment->setUser($this->getUser());
                $comment->setStatus(true);
                $commentRepository->save($comment, true);
                $this->addFlash('success', 'Votre commentaire à été posté merci!');
                return $this->render('home/comment.html.twig',[
                    "form" => $form->createView(),
                    "canPostComment" => $commentService->canPostComment($this->getUser())
                ]);
            }
        }
        $comments = $commentRepository->findBy(["status" => true], ["createdAt" => "DESC"]);
        return $this->render('home/comment.html.twig', [
            "comments" => $comments,
            "form" => $form->createView(),
            "canPostComment" => $commentService->canPostComment($this->getUser())
        ]);
    }
    #[Route('/add-payment/{id}', name: 'app_add_payment', methods: ['POST', 'GET'])]
    public function addPayment(
        int $id,
        DeclarationRepository $declarationRepository,
        FundRepository $fundRepository,
        TransactionService $transactionService,
        SmsService $smsService
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
        $to = new SmsResponse($declaration->getUser());
        $response =  $smsService->sendSms($to, $user->getTelephone(), $declaration->getLabel());
        if($response){
            $transactionService->create(
                $fund,
                500,
                Transaction::DEPOSIT,
                Transaction::DEPOSIT_FOR_PAYMENT .' '. $declaration->getLabel()
            );
        }
        $response = new ApiResponse();
        return $this->json($response);
    }
}
