<?php

namespace App\Service;

use App\Entity\Archive;
use App\Entity\Declaration;
use App\Entity\Document;
use App\Entity\User;
use App\Exceptions\ForbiddenException;
use Symfony\Component\Security\Core\User\UserInterface;

class UserService
{
    public function __construct()
    {

    }

    public function getIsOwner(UserInterface $user, $entity): void
    {
        if($entity instanceof Archive){
            $this->isDifferent($entity->getOwner()->getId(), $user->getId());
        }
        if($entity instanceof Document){
            $this->isDifferent($entity->getUser()->getId(), $user->getId());
        }
        if($entity instanceof Declaration){
            $this->isDifferent($entity->getUser()->getId(), $user->getId());
        }
    }

    private function isDifferent(int $entityUserId, int $userId): void
    {
        if($entityUserId !== $userId){
            throw new ForbiddenException();
        }
    }
}