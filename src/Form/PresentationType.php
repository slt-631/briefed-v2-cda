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
                'label' => false,
            ])
            ->add('background', BackgroundType::class, [
                'label' => false,
            ])
            ->add('format', ChoiceType::class, [
                'label' => false,
                'choices' => [
                    'Carré (1:1)' => 'carre',
                    'Paysage (16:9)' => 'paysage',
                    'Standard (4:3)' => 'standard',
                ],
                'expanded' => true,
                'multiple' => false,
                'choice_attr' => fn () => [
                    'data-presentation-editor-target' => 'formatInput',
                ],
            ])
            ->add('imageFile', FileType::class, [
                'label' => false,
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
            ->add('border', RangeType::class, [
                'label' => 'Bordure',
                'attr' => ['min' => 0, 'max' => 100, 'step' => 1],
            ])
            ->add('borderColor', ColorType::class, [
                'label' => 'Couleur de bordure',
            ])
            ->add('borderOpacity', RangeType::class, [
                'label' => 'Opacité',
                'attr' => ['min' => 0, 'max' => 100, 'step' => 1],
            ])
            ->add('radius', RangeType::class, [
                'label' => 'Radius',
                'attr' => ['min' => 0, 'max' => 100, 'step' => 1],
            ])
            ->add('shadowType', ChoiceType::class, [
                'label' => false,
                'choices' => [
                    'None' => 'none',
                    'Spread' => 'spread',
                    'Hug' => 'hug',
                ],
                'expanded' => true,
                'multiple' => false,
                'choice_attr' => fn () => [
                    'data-presentation-editor-target' => 'shadowTypeInput',
                ],
            ])
            ->add('shadowOpacity', RangeType::class, [
                'label' => 'Opacité',
                'attr' => ['min' => 0, 'max' => 100, 'step' => 1],
            ])
            ->add('shadowAngle', RangeType::class, [
                'label' => 'Direction',
                'attr' => ['min' => 0, 'max' => 360, 'step' => 1],
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
