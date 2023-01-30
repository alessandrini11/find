<?php

namespace App\Controller\User;

use App\Entity\Declaration;
use App\Form\DeclarationSearchType;
use App\Form\DeclarationType;
use App\Models\DeclarationSearch;
use App\Repository\DeclarationRepository;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/dashboard/declaration')]
class DeclarationController extends AbstractController
{
    #[Route('/', name: 'app_declaration_index', methods: ['GET'])]
    public function index(DeclarationRepository $declarationRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $declaration = new DeclarationSearch();
        $form = $this->createForm(DeclarationSearchType::class, $declaration);
        $form->handleRequest($request);
        $declarations = $paginator->paginate(
            $declarationRepository->searchDeclaration($declaration),
            1,
            10
        );
        return $this->render('declaration/index.html.twig', [
            'declarations' => $declarations,
            'search_form' => $form->createView()
        ]);
    }

    #[Route('/new', name: 'app_declaration_new', methods: ['GET', 'POST'])]
    public function new(Request $request, DeclarationRepository $declarationRepository, UserRepository $userRepository): Response
    {
        $user = $userRepository->find(3);

        $declaration = new Declaration();
        $form = $this->createForm(DeclarationType::class, $declaration);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $declaration->setUser($user);
            $declarationRepository->save($declaration, true);

            $this->addFlash('success', 'Déclaration ajouté avec succès');
            return $this->redirectToRoute('app_declaration_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('declaration/new.html.twig', [
            'declaration' => $declaration,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_declaration_show', methods: ['GET'])]
    public function show(Declaration $declaration): Response
    {
        return $this->render('declaration/show.html.twig', [
            'declaration' => $declaration,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_declaration_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Declaration $declaration, DeclarationRepository $declarationRepository, UserRepository $userRepository): Response
    {
        $user = $userRepository->find(3);
        $form = $this->createForm(DeclarationType::class, $declaration);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $declaration->setUser($user);
            $declarationRepository->save($declaration, true);

            $this->addFlash('success', 'Déclaration modifié avec succès');
            return $this->redirectToRoute('app_declaration_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('declaration/edit.html.twig', [
            'declaration' => $declaration,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_declaration_delete', methods: ['POST'])]
    public function delete(Request $request, Declaration $declaration, DeclarationRepository $declarationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$declaration->getId(), $request->request->get('_token'))) {
            $declarationRepository->remove($declaration, true);
        }

        return $this->redirectToRoute('app_declaration_index', [], Response::HTTP_SEE_OTHER);
    }
}
