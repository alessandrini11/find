<?php

namespace App\Controller\User;

use App\Entity\Archive;
use App\Form\ArchiveType;
use App\Repository\ArchiveRepository;
use App\Repository\UserRepository;
use App\Service\ArchiveService;
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
        $user = $userRepository->find(3);
        $archives = $archiveRepository->findRelated($user);
        return $this->render('archive/index.html.twig', [
            'archives' => $archives,
            'user' => $user,
        ]);
    }

    #[Route('/new', name: 'app_archive_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ArchiveRepository $archiveRepository, UserRepository $userRepository): Response
    {
        $user = $userRepository->find(3);
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
        UserRepository $userRepository,
        ArchiveService $archiveService
    ): Response
    {
        $archiveService->getValidationStatus($archive);
        $user = $userRepository->find(3);
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
        ]);
    }

    #[Route('/{id}', name: 'app_archive_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        Archive $archive,
        ArchiveRepository $archiveRepository,
        ArchiveService $archiveService,
        UserRepository $userRepository,
        UserService $userService
    ): Response
    {
        $archiveService->getValidationStatus($archive);
        $user = $userRepository->find(3);
        $userService->getIsOwner($user, $archive);
        if ($this->isCsrfTokenValid('delete'.$archive->getId(), $request->request->get('_token'))) {
            $archiveRepository->remove($archive, true);
        }

        return $this->redirectToRoute('app_archive_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/validate/{id}', name: 'app_archive_validate', methods: ['POST'])]
    public function validateArchive(int $id, Request $request, ArchiveService $archiveService, ArchiveRepository $archiveRepository): Response
    {
        $isOwner = json_decode($request->getContent())->isOwner;
        $archive = $archiveService->findOrFail($id, true);
        $archiveService->getValidationStatus($archive);
        if($isOwner){
            $archive->setOwnerValidation(true);
        } else {
            $archive->isOwnerValidation() ?? $archive->setActorValidation(true);
        }
        $archiveRepository->save($archive, true);
        return $this->json([
            'message' => 'updated'
        ],Response::HTTP_OK);
    }
}
