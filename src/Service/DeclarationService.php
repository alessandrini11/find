<?php

namespace App\Service;

use App\Entity\Declaration;
use App\Repository\DeclarationRepository;

class DeclarationService
{
    private DeclarationRepository $declarationRepository;

    public function __construct(DeclarationRepository $declarationRepository)
    {
        $this->declarationRepository = $declarationRepository;
    }

    public function setCompleted(Declaration $declaration): void
    {
        $declaration->setCompleted(true);
        $this->declarationRepository->save($declaration, true);
    }
}