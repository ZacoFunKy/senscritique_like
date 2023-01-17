<?php

namespace App\Controller\Admin;

use App\Entity\Series;
use App\Entity\User;
use App\Entity\Rating;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use Symfony\Component\Security\Core\User\UserInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;


class DashboardController extends AbstractDashboardController
{
    public function __construct(
        private AdminUrlGenerator $adminUrlGenerator
    ) {
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $routeBuilder = $this->adminUrlGenerator
            ->setController(UserCrudController::class)
            ->setAction(Crud::PAGE_INDEX);

        return $this->redirect($routeBuilder->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Home');


    }

    public function configureMenuItems(): iterable
    {
        return [
            MenuItem::linktoDashboard('Dashboard', 'fa fa-home'),
            MenuItem::subMenu('Users', 'fa fa-users')->setSubItems([
                MenuItem::linkToCrud('Utilisateurs', 'fa fa-user', User::class),
                MenuItem::linkToRoute('Ajouter', 'fa-solid fa-robot', 'app_admin_user_new'),
                MenuItem::linkToRoute('Supprimer', 'fa fa-trash', 'app_admin_user_delete'),
            ]),
            MenuItem::subMenu('Commentaires', 'fa fa-comments')->setSubItems([
                MenuItem::linkToCrud('Commentaires', 'fa fa-comment', Rating::class),
                MenuItem::linkToCrud('Ajouter', 'fa-solid fa-robot', Series::class),
                MenuItem::linkToRoute('Supprimer', 'fa fa-trash', 'app_admin_user_comment_delete'),
            ])
            ,
            MenuItem::subMenu('Series', 'fa-solid fa-tv')->setSubItems([
                MenuItem::linkToRoute('Ajouter', 'fa-solid fa-plus-large', 'app_admin_user_series_new'),
            ])
            ,
            MenuItem::linkToUrl('Retour au site', 'fa fa-arrow-left', '/'),

        ];
    }

    public function configureAssets(): Assets
    {
        return Assets::new()->addCssFile('style/admin.css');
    }
    
}