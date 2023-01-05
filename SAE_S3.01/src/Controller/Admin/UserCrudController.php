<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;



class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->onlyOnIndex(),
            EmailField::new('email')
                ->setRequired(true)
                ->setFormTypeOptions(['disabled' => true]),
            TextField::new('password')
                ->setRequired(true)
                ->setFormTypeOptions(['disabled' => true]),
            BooleanField::new('isAdmin')
                ->setRequired(true)
                ->setPermission('ROLE_SUPER_ADMIN'),
            BooleanField::new('isSuperAdmin')
                ->setRequired(true)
                ->setFormTypeOptions(['disabled' => true]),
            CollectionField::new('roles'),
            
            
        ];
    }
    
}