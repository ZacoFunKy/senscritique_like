<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        if ($this->isGranted('IS_IMPERSONATOR') && $this->isGranted('ROLE_SUPER_ADMIN')) {
            return [
            IdField::new('id')
                ->onlyOnIndex()
                ->setSortable(true),
            TextField::new('name'),
            EmailField::new('email')
                ->setRequired(true)
                ->setFormTypeOptions(['disabled' => true])
                ->setSortable(true),
            BooleanField::new('Suspendu')
                ->setRequired(true)
                ->setPermission('ROLE_ADMIN'),
            BooleanField::new('isBot')
                ->setFormTypeOptions(['disabled' => true])
                ->setSortable(true),
            BooleanField::new('isAdmin')
                ->setRequired(true)
                ->setFormTypeOptions(['disabled' => true])
                ->setSortable(false),
            BooleanField::new('isSuperAdmin')
                ->setRequired(true)
                ->setFormTypeOptions(['disabled' => true]),
            CollectionField::new('roles'),
            ];
        } else {
            return [
                IdField::new('id')
                    ->onlyOnIndex(),
                TextField::new('name'),
                EmailField::new('email')
                    ->setRequired(true)
                    ->setFormTypeOptions(['disabled' => true]),
                BooleanField::new('Suspendu')
                    ->setRequired(true)
                    ->setPermission('ROLE_ADMIN')
                    ->addHtmlContentsToBody("<script>
                    // Recuperer toute checkbox qui est dans le td avec le data-label='Suspendu' et  la div avec la class= form-check form-switch 
                    //(plusieurs checkbox peuvent avoir cette class)
                    // Pour chaque checkbox, on ajoute un event listener sur le click
                    var checkboxes = document.querySelectorAll('td[data-label=Suspendu] div.form-check.form-switch input');
                    checkboxes.forEach(checkbox => {
                        // On ajoute un event listener sur le click
                        checkbox.addEventListener('click', function(e) {
                            if (checkbox.checked) {
                                var rep = confirm('Voulez-vous vraiment suspendre cet utilisateur ?');
                                // si on clique sur annuler
                                if (rep == false) {
                                    checkbox.checked = false;
                                } else {
                                    var id = checkbox.closest('tr').getAttribute('data-id');
                                    window.location.href = '/user/suspended/' + id + '/' + 1;
                                    checkbox.checked = true;
                                }
                            } else {
                                var rep = confirm('Voulez-vous vraiment réactiver cet utilisateur ?');
                                // si on clique sur annuler
                                if (rep == false) {
                                    checkbox.checked = true;
                                } else {
                                    var id = checkbox.closest('tr').getAttribute('data-id');
                                    window.location.href = '/user/suspended/' + id + '/' + 0;
                                    checkbox.checked = false;
                                }
                            }
                        });
                    });
                    </script>"),
                BooleanField::new('isBot')
                    ->setFormTypeOptions(['disabled' => true])
                    ->setSortable(true),
                BooleanField::new('isAdmin')
                    ->setRequired(true)
                    ->setPermission('ROLE_SUPER_ADMIN'),
                BooleanField::new('isSuperAdmin')
                    ->setRequired(true)
                    ->setFormTypeOptions(['disabled' => true]),
                CollectionField::new('roles')
                    ->setFormTypeOptions(['disabled' => true])
                    ->setPermission('ROLE_SUPER_ADMIN'),
            ];
        }
    }

    public function configureActions(Actions $actions): Actions
    {
        //Permet d'incarner un utilisateur en tant qu'administrateur
        $impersonate = Action::new('impersonate', 'Incarner', 'fas fa-user-secret')
        ->linkToUrl(function (User $entity) {
            return 'series/?_switch_user='.$entity->getEmail();
        });

    return parent::configureActions($actions)
        ->add(Crud::PAGE_INDEX, $impersonate)
        ->update(Crud::PAGE_INDEX, Action::DELETE, function (Action $action) {
            return $action
                ->setLabel('Supprimer')
                ->setIcon('fa fa-trash')
                ->displayIf(fn (User $user) => $this->isGranted('ROLE_SUPER_ADMIN'));
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
                ->displayIf(fn (User $user) => $this->isGranted('ROLE_SUPER_ADMIN'))
                ->linkToRoute('app_admin_user_new');
        })
        ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
            return $action
                ->setLabel('Nombre de faux comptes')
                ->setIcon('fa fa-user-secret')
                ->linkToRoute('app_admin_user_count_fake_accounts');
        });
    }
}