<?php

namespace App\Form;

use App\Entity\Series;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SeriesType extends AbstractType
{
    /*
    * Formulaire de série
    */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /*
        * title : le titre
        * plot : le plot
        * imdb : le code imdb
        * poster : le poster
        * director : le réalisateur
        * youtubeTrailer : la bande annonce
        * awards : les récompenses remportées
        * yearStart : l'année de sorti
        * country : le pays de réalisation
        * user : les utilisateurs qui suivent la série
        * actor : les acteurs
        * genre : le genre
        */
        $builder
            ->add('title')
            ->add('plot')
            ->add('imdb')
            ->add('poster')
            ->add('director')
            ->add('youtubeTrailer')
            ->add('awards')
            ->add('yearStart')
            ->add('yearEnd')
            ->add('country')
            ->add('user')
            ->add('actor')
            ->add('genre')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Series::class,
        ]);
    }
}
