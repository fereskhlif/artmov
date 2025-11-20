<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;


class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('prix')
            ->add('lieu')
            ->add('image', FileType::class, [
                'label' => 'Event Image (JPEG, PNG, etc.)',
                'mapped' => false, // Because this field is not mapped to the entity directly
                'required' => false, // Optional, you can set this to true if an image is mandatory
                'attr' => ['accept' => 'image/*'], // Ensure only image files can be selected
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
