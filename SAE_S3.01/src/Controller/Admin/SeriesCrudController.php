<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use App\Entity\Series;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;

class SeriesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Series::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            DateField::new('yearStart'),
            DateField::new('yearEnd'),
        ];
    }*/


    public function configureActions(Actions $actions): Actions
    {
        $avis = Action::new('avis', 'Avis', 'fa fa-comment')
            ->linkToUrl(function (Series $series) {
                return $this->generateUrl('app_admin_user_comment_new', ['id' => $series->getId()]);
            });

    return parent::configureActions($actions)
        ->add(Crud::PAGE_INDEX, $avis)
        ->update(Crud::PAGE_INDEX, Action::DELETE, function (Action $action) {
            return $action
                ->setLabel('Supprimer')
                ->setIcon('fa fa-trash');
        })
        ->update(Crud::PAGE_INDEX, Action::EDIT, function (Action $action) {
            return $action
                ->setLabel('Modifier')
                ->setIcon('fa fa-edit');
        })
        ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
            return $action
                ->setLabel('Ajouter')
                ->setIcon('fa fa-plus')
                ->linkToRoute('app_admin_user_new');
        })
        ->remove(Crud::PAGE_INDEX, Action::NEW);
    }
        
}
