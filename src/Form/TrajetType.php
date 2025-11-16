<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
                'attr' => [
                    'class' => 'form-control form-control-sm', // version compacte Bootstrap
                    'style' => 'width: 200px;' // largeur personnalisée
                ]
            ])
            ->add('date_arr', DateTimeType::class, [
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control form-control-sm',
                    'style' => 'width: 200px;'
                ]
            ])
            ->add('ville_dep', null, [
                'attr' => [
                    'class' => 'form-control form-control-sm',
                    'style' => 'width: 150px;'
                ]
            ])
            ->add('ville_arr', null, [
                'attr' => [
                    'class' => 'form-control form-control-sm',
                    'style' => 'width: 150px;'
                ]
            ])
            ->add('vehicule', EntityType::class, [
                'class' => Vehicule::class,
                'choice_label' => 'matricule',
                'attr' => [
                    'class' => 'form-control form-control-sm',
                    'style' => 'width: 150px;'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Ajouter Trajet',
                'attr' => ['class' => 'btn btn-primary btn-sm mt-3']
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Trajet::class,
        ]);
    }
}
