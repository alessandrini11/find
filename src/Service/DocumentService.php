<?php

namespace App\Service;

use App\Entity\Document;
use App\Entity\User;
use App\Repository\DocumentRepository;
use Symfony\Component\Form\FormInterface;

class DocumentService
{
    private DocumentRepository $documentRepository;
    private UploadService $uploadService;

    /**
     * @param DocumentRepository $documentRepository
     * @param UploadService $uploadService
     */
    public function __construct(DocumentRepository $documentRepository, UploadService $uploadService)
    {
        $this->documentRepository = $documentRepository;
        $this->uploadService = $uploadService;
    }


    public function create(FormInterface $form, User $user): Document
    {
        $document = $this->setFields(new Document(), $form);
        $document
            ->setUser($user)
        ;
        $this->documentRepository->save($document, true);
        return $document;

    }

    public function edit(FormInterface $form, Document $document): Document
    {
        $editedDocument = $this->setFields($document, $form);
        $this->documentRepository->save($editedDocument, true);
        return $document;
    }

    private function setFields(Document $document, FormInterface $form): Document
    {
        $document
            ->setIdNumber($form->get('idNumber')->getData())
            ->setOwner($form->get('owner')->getData())
            ->setType($form->get('type')->getData())
            ->setDescription('null');

        if($form->get('imageFile')->getData() !== null){
            $fileName = $this->uploadService->uploadFile($form->get('imageFile')->getData(), Document::DIRECTORY);
            $document->setImage($fileName);
        }

        return $document;
    }
}