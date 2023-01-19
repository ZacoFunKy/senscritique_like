<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SerieAddFormType extends AbstractType
{
    /*
    * Formulaire d'ajout d'une série
    */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        // title : titre de la série souhaitée
            ->add('title')
            ;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}
