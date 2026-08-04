<?php

namespace App\Form;

use App\Entity\Presentation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Form\Extension\Core\Type\RangeType;


class PresentationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
            ])
            ->add('backgroundColor', ColorType::class, [
                'label' => 'Couleur de fond',
            ])
            ->add('format', ChoiceType::class, [
                'label' => 'Format',
                'choices' => [
                    'Carré (1:1)' => '1:1',
                    'Paysage (16:9)' => '16:9',
                    'Standard (4:3)' => '4:3',
                ],
            ])
            ->add('imageFile', FileType::class, [
                'label' => 'Image',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new Image(
                        maxSize: '2M',
                        maxSizeMessage: 'L\'image ne doit pas dépasser {{ maxSize }}.',
                        mimeTypes: ['image/jpeg', 'image/png', 'image/webp'],
                        mimeTypesMessage: 'Formats acceptés : JPEG, PNG, WebP.',
                    ),
                ],
            ])

            ->add('posX', RangeType::class, [
                'label' => 'Position X',
                'attr' => ['min' => -800, 'max' => 800, 'step' => 1],
            ])
            ->add('posY', RangeType::class, [
                'label' => 'Position Y',
                'attr' => ['min' => -450, 'max' => 450, 'step' => 1],
            ])
            ->add('scale', RangeType::class, [
                'label' => 'Échelle',
                'attr' => ['min' => 0.1, 'max' => 5, 'step' => 0.1],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Presentation::class,
        ]);
    }
}
