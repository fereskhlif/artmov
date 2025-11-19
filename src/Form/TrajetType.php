<?php

namespace App\Form;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use App\Entity\Trajet;
use App\Entity\Vehicule;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrajetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date_dep', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Date de départ',
                'row_attr' => [
                    'class' => 'col-md-6 mb-3'  // 50% de largeur sur desktop
                ],
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('date_arr', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Date d\'arrivée',
                'row_attr' => [
                    'class' => 'col-md-6 mb-3'  // 50% de largeur sur desktop
                ],
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('ville_dep', TextType::class, [
                'label' => 'Ville de départ',
                'row_attr' => [
                    'class' => 'col-md-6 mb-3'
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Ex: Tunis'
                ]
            ])
            ->add('ville_arr', TextType::class, [
                'label' => 'Ville d\'arrivée',
                'row_attr' => [
                    'class' => 'col-md-6 mb-3'
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Ex: Sousse'
                ]
            ])
            ->add('vehicule', EntityType::class, [
                'class' => Vehicule::class,
                'choice_label' => 'matricule',
                'label' => 'Véhicule',
                'row_attr' => [
                    'class' => 'col-12 mb-3'
                ],
                'attr' => [
                    'class' => 'form-select'
                ],
                'placeholder' => 'Sélectionnez un véhicule'
            ])
            ->add('nb_places', IntegerType::class, [
                'label' => 'Nombre de places',
                'row_attr' => ['class' => 'col-md-6 mb-3'],
                'attr' => [
                    'class' => 'form-control',
                    'min' => 1
                ],
                'empty_data' => 0,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Ajouter le trajet',
                'row_attr' => [
                    'class' => 'col-12 mb-3'
                ],
                'attr' => [
                    'class' => 'btn btn-secondary'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Trajet::class,
        ]);
    }
}
