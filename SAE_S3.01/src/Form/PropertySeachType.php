<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use App\Entity\PropertySearch;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PropertySeachType extends AbstractType
{
    /*
    * Formulaire pour effectuer une recherche avancée
    */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // genre : le genre des séries recherchées
        $builder
        ->add('genre', ChoiceType::class, [
            'required' => false,
            'choices' => [
                'Animation' => 'Animation',
                'Action' => 'Action',
                'Adventure' => 'Adventure',
                'Drama' => 'Drama',
                'Fantasy' => 'Fantasy',
                'Horror'=>'Horror',
                'Comedy'=>'Comedy',
                'Romance'=>'Romance',
                'Sci-Fi'=>'Sci-Fi',
                'Crime'=>'Crime',
                'Thriller'=>'Thriller',
                'Mystery'=>'Mystery',
                'Family'=>'Family',
                'Biography'=>'Biography',
                'War'=>'War',
                'History'=>'History',
                'Western'=>'Western',
                'Music'=>'Music',
                'Documentary'=>'Documentary',
                'Musical'=>'Musical',
                'Short'=>'Short',
                'Talk-Show'=>'Talk-Show'
            ],
        ])
        // anneeDepart : l'année après laquelle les séries sont sorties
        ->add('anneeDepart', IntegerType::class, [
            'required' => false,
            'label' => false,
            'attr' => [
                'placeholder' => 'Sorti après le...'
            ]
        ]
        )
        // anneeFin : l'année avant laquelle les séries sont sorties
        ->add('anneeFin', IntegerType::class, [
            'required' => false,
            'label' => false,
            'attr' => [
                'placeholder' => 'Sorti avant le...'
            ]
        ]
        )
        // avis : pour avoir les séries avec une certaine note
        ->add('avis', ChoiceType::class, [
            'required' => false,
            'choices' => [
                    'Croissant' => 'ASC',
                    'Décroissant' => 'DESC',
                    '4-5 étoiles' => 5,
                    '3-4 étoiles' => 4,
                    '2-3 étoiles' => 3,
                    '1-2 étoiles' => 2,
                    '0-1 étoile' => 1,
            ],
        ]
        )
        // suivi : pour avoir les séries suivies par l'utilisateur
        ->add('suivi', CheckboxType::class, [
            'required' => false,
            'label' => "Suivis",
        ]
        )
        // nom : pour avoir les séries commençant par "nom"
        ->add('nom', TextType::class, [
            'required' => false,
            'label' => false,
            'attr' => [
                'placeholder' => 'Rechercher une série'
            ]
        ]
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PropertySearch::class,
            'method' => 'get',
            'csrf_protection' => false,
        ]);
    }
}