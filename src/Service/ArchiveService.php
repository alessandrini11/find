<?php

namespace App\Service;

use App\Entity\Archive;
use App\Entity\Declaration;
use App\Entity\User;
use App\Exceptions\Api\ForbiddenException;
use App\Exceptions\Api\NotFoundException as ApiNotFoundException;
use App\Exceptions\NotFoundException;
use App\Repository\ArchiveRepository;

class ArchiveService
{
    private ArchiveRepository $archiveRepository;

    /**
     * @param ArchiveRepository $archiveRepository
     */
    public function __construct(ArchiveRepository $archiveRepository)
    {
        $this->archiveRepository = $archiveRepository;
    }

    public function findOrFail(int $id, bool $isApi = false): Archive
    {
        $archive = $this->archiveRepository->find($id);
        if (!$archive){
            if($isApi){
                throw new ApiNotFoundException();
            }
            throw new NotFoundException();
        }
        return $archive;
    }

    public function getUserToPay(Archive $archive): User
    {
        $declaration = $archive->getDeclaration();
        if($declaration->getStatus() === Declaration::LOST)
        {
            return $archive->getActor();
        }
        return $archive->getOwner();
    }

    public function getValidationStatus(Archive $archive): bool
    {
        if($archive->isActorValidation() && $archive->isOwnerValidation()){
            throw new ForbiddenException();
        }
        return true;
    }

}