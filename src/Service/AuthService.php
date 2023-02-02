<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AuthService extends AbstractController
{
    private UserPasswordHasherInterface $encoder;
    private UserRepository $userRepository;

    public function __construct(UserPasswordHasherInterface $encoder, UserRepository $userRepository)
    {
        $this->encoder = $encoder;
        $this->userRepository = $userRepository;
    }

    public function updatePassword(FormInterface $form,User $user): Response
    {
        if($form->isSubmitted() && $form->isValid())
        {
            $actualPassword = $form->get('actualPassword')->getData();
            $isEqual = $this->encoder->isPasswordValid($user, $actualPassword);

            if($isEqual){
                $plainPassword = $form->get('plainPassword')->getData();
                $newHash = $this->encoder->hashPassword($user, $plainPassword);
                $user->setPassword($newHash);
                $this->userRepository->save($user, true);
                $this->addFlash('success_change_password', 'le mot de passe a été modifié');
            } else {
                $this->addFlash('error', 'Ancien mot de password invalide');
            }
        }
        return $this->redirectToRoute('app_dashboard_profile',[]);
    }

    public function updateUserInfos(FormInterface $form, User $user): Response
    {
        if($form->isSubmitted() && $form->isValid()){
            $this->userRepository->save($user, true);
            $this->addFlash('success', 'les informations ont été mises à jour');
        }
        return $this->redirectToRoute('app_dashboard_profile',[]);
    }
}