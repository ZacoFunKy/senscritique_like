<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use Symfony\Component\Security\Core\User\UserInterface;


class DashboardController extends AbstractDashboardController
{
    public function __construct(
        private AdminUrlGenerator $adminUrlGenerator
    ) {
    }

    #[Route('/admin/dashboard', name: 'admin')]
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
                MenuItem::linkToCrud('Permissions', 'fa fa-user', User::class),
            ])->setPermission('ROLE_ADMIN')
            ,
            MenuItem::linkToRoute('Back to site', 'fa fa-home', 'app_series_index'),            

        ];
    }

    public function configureUserMenu(UserInterface $user): UserMenu
    {
        return parent::configureUserMenu($user)
        ->addMenuItems([
            MenuItem::linkToRoute('Back to site', 'fa-solid fa-earth-americas', 'app_series_index'),            
        ])
        ;
    }
    
}