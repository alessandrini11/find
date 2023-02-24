<?php

namespace App\Service;

use App\Repository\CommentRepository;
use Symfony\Component\Security\Core\User\UserInterface;

class CommentService
{
    private CommentRepository $commentRepository;

    public function __construct(CommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    public function getGeneralNotation(): float
    {
        $comments = $this->commentRepository->findAll();
        $totalComments = 0;
        foreach ($comments as $comment)
        {
            $totalComments += $comment->getNotation();
        }
        return round($totalComments / count($comments), 1);
    }

    public function canPostComment(UserInterface $user = null): bool
    {
        if ($user){
            $comment = $this->commentRepository->findOneBy(["user" => $user]);
            if (!$comment){
                return true;
            }
        }
        return false;
    }
}