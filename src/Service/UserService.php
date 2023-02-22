<?php

namespace App\Service;

use App\Entity\Archive;
use App\Entity\Declaration;
use App\Entity\Document;
use App\Entity\User;
use App\Exceptions\ForbiddenException;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\User\UserInterface;

class UserService extends BaseService
{
    private UserRepository $userRepository;
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
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

    public function deleteUnverifiedUsers(): array
    {
        $users = $this->userRepository->deleteUserByDateAndStatus();
        $count = 0;

        foreach ($users as $user)
        {
            $this->userRepository->remove($user, true);
            $count = $count + 1;
        }

        return ["removed" => $count, "total" => count($users)];
    }
    private function isDifferent(int $entityUserId, int $userId): void
    {
        if($entityUserId !== $userId){
            throw new ForbiddenException();
        }
    }

}