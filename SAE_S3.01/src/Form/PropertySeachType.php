<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use App\Entity\PropertySearch;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PropertySeachType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
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
        ->add('anneeDeSortie', ChoiceType::class, [
            'required' => false,
            'choices' => [
                'Croissant' => 'ASC',
                'Décroissant' => 'DESC'
            ],
        ])
        //->add('save', SubmitType::class)
            /*->add('nom', null, [
            'required' => false,
            'label' => false,
            'attr' => [
                'placeholder' => 'Rechercher une série'
            ]
            ])
            ->add('dateSortie', null, [
                'required' => false,
                'label' => false
            ])*/

                ->add('nom', TextType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Rechercher une série'
                ]
            ]
            )
                ->add('avis', ChoiceType::class, [
                'required' => false,
                'choices' => [
                    'Tous' => null,
                    'Croissant' => 'ASC',
                    'Décroissant' => 'DESC',
                    '5 étoiles' => 5,
                    '4 étoiles' => 4,
                    '3 étoiles' => 3,
                    '2 étoiles' => 2,
                    '1 étoile' => 1,
                ],
            ])
        ;
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