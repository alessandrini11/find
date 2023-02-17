<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig');
    }

    #[Route('/a-propos', name: 'app_about', methods: 'GET')]
    public function about(): Response
    {
        return $this->render('home/about.html.twig');
    }

    #[Route('/mentions-legales', name: 'app_mentions', methods: 'GET')]
    public function mentions(): Response
    {
        return $this->render('home/mentions.html.twig');
    }

    #[Route('/contact', name: 'app_contact', methods: 'GET')]
    public function contact(): Response
    {
        return $this->render('home/contact.html.twig');
    }
}
