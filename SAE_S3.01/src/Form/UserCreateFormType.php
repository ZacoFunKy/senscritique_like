<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserCreateFormType extends AbstractType
{
    /*
    * Formulaire de création d'utilisatuer
    */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /*
        * name : le nom
        * email : l'email
        * number : le nombre d'utilisateurs à créer
        */
        $builder
            ->add('name')
            ->add('email')
            ->add('number');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}
