<?php

namespace App\Controller\Admin;

use App\Entity\Rating;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;

class RatingCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Rating::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IntegerField::new('value'),
            TextField::new('comment'),
            BooleanField::new('verified'),
            TextField::new('getSeriesName'),
            TextField::new('getUserName'),
        ];
    }
}
