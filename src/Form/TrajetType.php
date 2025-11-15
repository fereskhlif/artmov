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
            ->add('date_dep', DateTimeType::class, ['widget' => 'single_text'])
            ->add('date_arr', DateTimeType::class, ['widget' => 'single_text'])
            ->add('ville_dep')
            ->add('ville_arr')
            ->add('vehicule', EntityType::class, [
                'class' => Vehicule::class,
                'choice_label' => 'matricule',
            ])
            ->add('submit', SubmitType::class, ['label' => 'Ajouter Trajet']);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Trajet::class,
        ]);
    }
}
