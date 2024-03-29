<?php

namespace App\Controller\User;

use App\Entity\Document;
use App\Form\DocSearchType;
use App\Form\DocumentType;
use App\Models\DocumentSearch;
use App\Repository\DocumentRepository;
use App\Service\UploadService;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/dashboard/document')]
class DocumentController extends AbstractController
{
    #[Route('/', name: 'app_document_index', methods: ['GET'])]
    public function index(DocumentRepository $documentRepository, Request $request): Response
    {
        $user = $this->getUser();
        $documents = $user->getDocuments();
        $docSearch = new DocumentSearch();
        $form = $this->createForm(DocSearchType::class, $docSearch);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $documents = $documentRepository->searchDocuments($docSearch, $user);
            return $this->render('document/index.html.twig', [
                'documents' => $documents->getArrayResult(),
                'search_form' => $form->createView(),
                'page_name' => 'documents',
                'page_route' => 'app_document_index'
            ]);
        }
        return $this->render('document/index.html.twig', [
            'documents' => $documents,
            'search_form' => $form->createView(),
            'page_name' => 'documents',
            'page_route' => 'app_document_index'
        ]);
    }

    #[Route('/{id}', name: 'app_document_show', methods: ['GET'])]
    public function show(Document $document): Response
    {
        return $this->render('document/show.html.twig', [
            'document' => $document,
            'page_name' => 'documents',
            'page_route' => 'app_document_index',
            'sub_page' => $document->getId(),
            'sub_route' => 'app_document_show'
        ]);
    }
    #[Route('/{id}', name: 'app_document_delete', methods: ['POST'])]
    public function delete(Request $request, Document $document, DocumentRepository $documentRepository, UserService $userService): Response
    {
        $user = $this->getUser();
        $userService->getIsOwner($user, $document);
        if ($this->isCsrfTokenValid('delete'.$document->getId(), $request->request->get('_token'))) {
            $documentRepository->remove($document, true);
            $this->addFlash('success', 'Document supprimé avec succès');
        }
        return $this->redirectToRoute('app_document_index', [], Response::HTTP_SEE_OTHER);
    }

//    #[Route('/new', name: 'app_document_new', methods: ['GET', 'POST'])]
//    public function new(
//        Request $request,
//        DocumentRepository $documentRepository,
//        UploadService $uploadService,
//    ): Response
//    {
//        $user = $this->getUser();
//        $document = new Document();
//        $form = $this->createForm(DocumentType::class, $document);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $imageFile = $form->get('imageFile')->getData();
//            $fileName = $uploadService->uploadFile($imageFile, Document::DIRECTORY);
//            $document->setImage($fileName);
//            $document->setUser($user);
//            $documentRepository->save($document, true);
//
//            $this->addFlash('success', 'Document créé avec succès');
//            return $this->redirectToRoute('app_document_index', [], Response::HTTP_SEE_OTHER);
//        }
//
//        return $this->renderForm('document/new.html.twig', [
//            'document' => $document,
//            'form' => $form,
//            'page_name' => 'documents',
//            'page_route' => 'app_document_index',
//            'sub_page' => 'nouveau',
//            'sub_route' => 'app_document_new'
//        ]);
//    }



//    #[Route('/{id}/edit', name: 'app_document_edit', methods: ['GET', 'POST'])]
//    public function edit(
//        Request $request,
//        Document $document,
//        DocumentRepository $documentRepository,
//        UserService $userService,
//    ): Response
//    {
//        $user = $this->getUser();
//        $userService->getIsOwner($user, $document);
//        $form = $this->createForm(DocumentType::class, $document);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $documentRepository->save($document, true);
//
//            $this->addFlash('success', 'Document modifié avec succès');
//            return $this->redirectToRoute('app_document_index', [], Response::HTTP_SEE_OTHER);
//        }
//
//        return $this->renderForm('document/edit.html.twig', [
//            'document' => $document,
//            'form' => $form,
//            'page_name' => 'documents',
//            'page_route' => 'app_document_index',
//            'sub_page' => 'modifier',
//            'sub_route' => 'app_document_edit',
//            'entity_id' => $document->getId()
//        ]);
//    }

}
