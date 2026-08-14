<?php

namespace App\Form;

use App\Entity\Background;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class BackgroundType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type', ChoiceType::class, [
                'label' => false,
                'choices' => [
                    'Solide' => 'solid',
                    'Dégradé' => 'degrade',
                    'Image' => 'image',
                ],
                'expanded' => true,
                'multiple' => false,
                'choice_attr' => fn () => [
                    'data-presentation-editor-target' => 'bgTypeInput',
                ],
            ])
            ->add('color1', ColorType::class, [
                'label' => false,
            ])
            ->add('color2', ColorType::class, [
                'label' => false,
            ])
            ->add('gradientAngle', RangeType::class, [
            'label' => 'Direction',
            'attr' => ['min' => 0, 'max' => 360, 'step' => 1],
            ])
            ->add('backgroundImageFile', FileType::class, [
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Background::class,
        ]);
    }
}
