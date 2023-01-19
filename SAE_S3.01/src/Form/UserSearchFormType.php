<?php

namespace App\Form;

use App\Entity\UserSearch;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserSearchFormType extends AbstractType
{
    /*
    * Formulaire de recherche d'utilisateur
    */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // nom : le nom recherché
        $builder
                ->add('nom', null, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Rechercher un utilisateur'
                ]
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserSearch::class,
        ]);
    }
}
