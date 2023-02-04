<?php

namespace App\Controller\User;

use App\Entity\Archive;
use App\Entity\Transaction;
use App\Form\ArchiveType;
use App\Repository\ArchiveRepository;
use App\Repository\FundRepository;
use App\Repository\UserRepository;
use App\Service\ArchiveService;
use App\Service\DeclarationService;
use App\Service\TransactionService;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/dashboard/archive')]
class ArchiveController extends AbstractController
{
    #[Route('/', name: 'app_archive_index', methods: ['GET'])]
    public function index(ArchiveRepository $archiveRepository, UserRepository $userRepository): Response
    {
        $user = $this->getUser();
        $archives = $archiveRepository->findRelated($user);
        return $this->render('archive/index.html.twig', [
            'archives' => $archives,
            'user' => $user,
            'page_name' => 'archives',
            'page_route' => 'app_archive_index'
        ]);
    }

    #[Route('/new', name: 'app_archive_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ArchiveRepository $archiveRepository ): Response
    {
        $user = $this->getUser();
        $archive = new Archive();
        $form = $this->createForm(ArchiveType::class, $archive);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $archive->setOwnerValidation(false);
            $archive->setActorValidation(false);
            $archive->setOwner($user);
            $archiveRepository->save($archive, true);

            $this->addFlash('success', 'Votre déclaration a été archivée avec succès');
            return $this->redirectToRoute('app_archive_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('archive/new.html.twig', [
            'archive' => $archive,
            'form' => $form,
            'page_name' => 'archives',
            'page_route' => 'app_archive_index',
            'sub_page' => 'nouveau',
            'sub_route' => 'app_archive_new'
        ]);
    }

//    #[Route('/{id}', name: 'app_archive_show', methods: ['GET'])]
//    public function show(Archive $archive, ): Response
//    {
//        return $this->render('archive/show.html.twig', [
//            'archive' => $archive,
//        ]);
//    }

    #[Route('/{id}/edit', name: 'app_archive_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Archive $archive,
        ArchiveRepository $archiveRepository,
        UserService $userService,
        ArchiveService $archiveService
    ): Response
    {
        $archiveService->getValidationStatus($archive);
        $user = $this->getUser();
        $userService->getIsOwner($user, $archive);
        $form = $this->createForm(ArchiveType::class, $archive);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $archive->setOwnerValidation(false);
            $archive->setActorValidation(false);
            $archiveRepository->save($archive, true);

            $this->addFlash('success', 'Votre déclaration a été modifiée avec succès');
            return $this->redirectToRoute('app_archive_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('archive/edit.html.twig', [
            'archive' => $archive,
            'form' => $form,
            'page_name' => 'archives',
            'page_route' => 'app_archive_index',
            'entity_id' => $archive->getId()
        ]);
    }

    #[Route('/{id}', name: 'app_archive_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        Archive $archive,
        ArchiveRepository $archiveRepository,
        ArchiveService $archiveService,
        UserService $userService
    ): Response
    {
        $archiveService->getValidationStatus($archive);
        $user = $this->getUser();
        $userService->getIsOwner($user, $archive);
        if ($this->isCsrfTokenValid('delete'.$archive->getId(), $request->request->get('_token'))) {
            $archiveRepository->remove($archive, true);
        }

        return $this->redirectToRoute('app_archive_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/validate/{id}', name: 'app_archive_validate', methods: ['POST'])]
    public function validateArchive(
        int $id,
        Request $request,
        ArchiveService $archiveService,
        ArchiveRepository $archiveRepository,
        TransactionService $transactionService,
        FundRepository $fundRepository,
        DeclarationService $declarationService
    ): Response
    {
        $isOwner = json_decode($request->getContent())->isOwner;
        $archive = $archiveService->findOrFail($id, true);
        $archiveService->getValidationStatus($archive);
        if($isOwner){
            $archive->setOwnerValidation(true);
        } else {
            if($archive->isOwnerValidation())
            {
                $archive->setActorValidation(true);
                $userToPay = $archiveService->getUserToPay($archive);
                $fund = $fundRepository->findOneBy(["user" => $userToPay]);
                $transactionService->create(
                    $fund,
                    50,
                    Transaction::TRANSFER,
                    Transaction::TRANSFER_FROM_ARCHIVES.' '. $archive->getDeclaration()->getLabel(),
                );
                $declarationService->setCompleted($archive->getDeclaration());
            }
        }
        $archiveRepository->save($archive, true);
        return $this->json([
            'message' => 'updated'
        ],Response::HTTP_OK);
    }
}
