<?php

namespace App\Controller\Admin;

use App\Entity\Archive;
use App\Entity\Comment;
use App\Entity\Declaration;
use App\Entity\Document;
use App\Entity\Fund;
use App\Entity\Municipality;
use App\Entity\Town;
use App\Entity\Transaction;
use App\Entity\User;
use App\Entity\Visitor;
use App\Repository\ArchiveRepository;
use App\Repository\CommentRepository;
use App\Repository\DeclarationRepository;
use App\Repository\DocumentRepository;
use App\Repository\UserRepository;
use App\Repository\VisitorRepository;
use App\Service\CommentService;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    private ArchiveRepository $archiveRepository;
    private DeclarationRepository $declarationRepository;
    private DocumentRepository $documentRepository;
    private UserRepository $userRepository;
    private VisitorRepository $visitorRepository;
    private CommentRepository $commentRepository;
    private CommentService $commentService;
    public function __construct(
        ArchiveRepository $archiveRepository,
        DeclarationRepository $declarationRepository,
        DocumentRepository $documentRepository,
        UserRepository $userRepository,
        VisitorRepository $visitorRepository,
        CommentRepository $commentRepository,
        CommentService $commentService
    )
    {
        $this->archiveRepository = $archiveRepository;
        $this->declarationRepository = $declarationRepository;
        $this->documentRepository = $documentRepository;
        $this->userRepository = $userRepository;
        $this->visitorRepository = $visitorRepository;
        $this->commentRepository = $commentRepository;
        $this->commentService = $commentService;
    }


    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {


        return $this->render('admin/dashboard.html.twig',[
            'found_documents' => $this->archiveRepository->findAll(),
            'declaration' => $this->declarationRepository->findAll(),
            'users' => $this->userRepository->findAll(),
            'visitors' => $this->visitorRepository->findAll(),
            'documents' => $this->documentRepository->findAll(),
            'general_notation' => $this->commentService->getGeneralNotation()
        ]);
        // return parent::index();
//        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
//        return $this->redirect($adminUrlGenerator->setController(UserCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureAssets(): Assets
    {
        return Assets::new()
                ->addCssFile('build/app.css');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Find')
            ->setLocales([
                'en' => '🇬🇧 English',
                'pl' => '🇵🇱 Polski'
            ]);
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::subMenu('Finance', 'fa-solid fa-money-check-dollar')->setSubItems([
            MenuItem::linkToCrud('Cagnote', 'fa-solid fa-money-bill', Fund::class),
            MenuItem::linkToCrud('Transactions', 'fa-solid fa-money-bill-transfer', Transaction::class)
        ]);
        yield MenuItem::subMenu('Activités', 'fa fa-house-user')->setSubItems([
            MenuItem::linkToCrud('Documents', 'fa-solid fa-book', Document::class),
            MenuItem::linkToCrud('Déclarations', 'fa-solid fa-newspaper', Declaration::class),
            MenuItem::linkToCrud('Archives', 'fa-solid fa-box-archive', Archive::class)
        ]);
        yield MenuItem::subMenu('Localisation', 'fa fa-map-pin')->setSubItems([
            MenuItem::linkToCrud('Municipalités', 'fa fa-map-pin', Municipality::class),
            MenuItem::linkToCrud('Villes', 'fa fa-map-pin', Town::class),
        ]);
        yield MenuItem::linkToCrud('Utilisateurs', 'fa fa-user', User::class);
        yield MenuItem::linkToCrud('Avis', 'fa fa-comment', Comment::class);
        yield MenuItem::linkToCrud('Visiteurs', 'fas fa-users', Visitor::class);
    }
    public function configureActions(): Actions
    {
        return parent::configureActions()->add(Crud::PAGE_INDEX, Action::DETAIL);
    }
}
