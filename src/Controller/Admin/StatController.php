<?php

namespace App\Controller\Admin;

use App\Controller\BienController;
use App\Entity\Admin;
use App\Entity\Bien;
use App\Entity\Categorie;
use App\Entity\ContactForm;
use App\Entity\FavorisSent;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StatController extends AbstractDashboardController
{

    public function __construct( private readonly AdminUrlGenerator $adminUrlGenerator)
    {

    }
    #[Route('/admin/', name: 'admin')]
    public function index(): Response
    {
    $routeBuilder = $this->adminUrlGenerator->setController(BienCrudController::class)->generateUrl();
    return $this->redirect($routeBuilder);


        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

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

    public function configureDashboard(): Dashboard
    {

        return Dashboard::new()
            ->setTitle('SaferProjetPW')
            ->setLocales(['en', 'pl']);


    }

    public function configureMenuItems(): iterable
    {
        #yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

            MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

            yield MenuItem::section('CRUD');

            yield MenuItem::section('Bien','fa fa-tags');

            yield MenuItem:: subMenu ('Actions', 'fas fa-bars')->setsubItems([
                MenuItem::linkTocrud('Voir', 'fas fa-eye', Bien::class)->setAction(Crud::PAGE_INDEX),
                MenuItem::linkTocrud(' Ajouter', 'fas fa-plus', Bien::class)->setAction(Crud::PAGE_NEW),
                #MenuItem::linkTocrud(' Edit product', 'fas fa-edit', Bien::class)->setAction(Crud::PAGE_EDIT),
            ]);
        yield MenuItem::section('Catégorie','fa fa-tags');

        yield MenuItem:: subMenu ('Actions', 'fas fa-bars')->setsubItems([
            MenuItem::linkTocrud('Voir', 'fas fa-eye', Categorie::class)->setAction(Crud::PAGE_INDEX),
            MenuItem::linkTocrud(' Ajouter', 'fas fa-plus', Categorie::class)->setAction(Crud::PAGE_NEW),
            #MenuItem::linkTocrud(' Edit product', 'fas fa-edit', Bien::class)->setAction(Crud::PAGE_EDIT),
        ]);
        yield MenuItem::section('Admin','fa fa-tags');

        yield MenuItem:: subMenu ('Actions', 'fas fa-bars')->setsubItems([
            MenuItem::linkTocrud('Voir', 'fas fa-eye', Admin::class)->setAction(Crud::PAGE_INDEX),
            MenuItem::linkTocrud(' Ajouter', 'fas fa-plus', Admin::class)->setAction(Crud::PAGE_NEW),
            #MenuItem::linkTocrud(' Edit product', 'fas fa-edit', Bien::class)->setAction(Crud::PAGE_EDIT),
        ]);

        yield MenuItem::section('Réceptions','fa fa-envelope');

        yield MenuItem:: subMenu ('Contact', 'fas fa-bars')->setsubItems([
            MenuItem::linkTocrud('Voir', 'fas fa-eye', ContactForm::class)->setAction(Crud::PAGE_INDEX),
            MenuItem::linkTocrud(' Ajouter', 'fas fa-plus', ContactForm::class)->setAction(Crud::PAGE_NEW),
            #MenuItem::linkTocrud(' Edit product', 'fas fa-edit', Bien::class)->setAction(Crud::PAGE_EDIT),
        ]);
        yield MenuItem:: subMenu ('Favoris', 'fas fa-bars')->setsubItems([
            MenuItem::linkTocrud('Voir', 'fas fa-eye', FavorisSent::class)->setAction(Crud::PAGE_INDEX),
            MenuItem::linkTocrud(' Ajouter', 'fas fa-plus', FavorisSent::class)->setAction(Crud::PAGE_NEW),
            #MenuItem::linkTocrud(' Edit product', 'fas fa-edit', Bien::class)->setAction(Crud::PAGE_EDIT),
        ]);

    }
}


