<?php

namespace App\Controller\User;

use App\Entity\Document;
use App\Form\DocSearchType;
use App\Form\DocumentType;
use App\Models\DocumentSearch;
use App\Repository\DocumentRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/dashboard/document')]
class DocumentController extends AbstractController
{
    #[Route('/', name: 'app_document_index', methods: ['GET'])]
    public function index(DocumentRepository $documentRepository, Request $request, UserRepository $userRepository): Response
    {
        $user = $userRepository->find(3);
        $docSearch = new DocumentSearch();
        $form = $this->createForm(DocSearchType::class, $docSearch);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $documents = $documentRepository->searchDocuments($docSearch);
            return $this->render('document/index.html.twig', [
                'documents' => $documents->getArrayResult(),
                'search_form' => $form->createView()
            ]);
        }
        return $this->render('document/index.html.twig', [
            'documents' => $documentRepository->findAll(),
            'search_form' => $form->createView()
        ]);
    }

    #[Route('/new', name: 'app_document_new', methods: ['GET', 'POST'])]
    public function new(Request $request, DocumentRepository $documentRepository, UserRepository $userRepository): Response
    {
        $user = $userRepository->find(3);
        $document = new Document();
        $form = $this->createForm(DocumentType::class, $document);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $document->setUser($user);
            $documentRepository->save($document, true);

            $this->addFlash('success', 'Document créé avec succès');
            return $this->redirectToRoute('app_document_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('document/new.html.twig', [
            'document' => $document,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_document_show', methods: ['GET'])]
    public function show(Document $document): Response
    {
        return $this->render('document/show.html.twig', [
            'document' => $document,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_document_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Document $document, DocumentRepository $documentRepository, UserRepository $userRepository): Response
    {
        $form = $this->createForm(DocumentType::class, $document);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $userRepository->find(3);
            $documentRepository->save($document, true);

            $this->addFlash('success', 'Document modifié avec succès');
            return $this->redirectToRoute('app_document_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('document/edit.html.twig', [
            'document' => $document,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_document_delete', methods: ['POST'])]
    public function delete(Request $request, Document $document, DocumentRepository $documentRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$document->getId(), $request->request->get('_token'))) {
            $documentRepository->remove($document, true);
        }

        return $this->redirectToRoute('app_document_index', [], Response::HTTP_SEE_OTHER);
    }
}
