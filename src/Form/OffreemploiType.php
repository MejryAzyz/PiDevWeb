<?php

namespace App\Form;

use App\Entity\Offreemploi;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;

class OffreemploiType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => 'Title',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Enter job title'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Please enter a title'])
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => [
                    'class' => 'form-control',
                    'rows' => '5',
                    'placeholder' => 'Enter job description'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Please enter a description'])
                ]
            ])
            ->add('typeposte', ChoiceType::class, [
                'label' => 'Job Type',
                'choices' => Offreemploi::JOB_TYPES,
                'attr' => [
                    'class' => 'form-select'
                ],
                'placeholder' => 'Select job type',
                'constraints' => [
                    new NotBlank(['message' => 'Please select a job type'])
                ]
            ])
            ->add('typecontrat', ChoiceType::class, [
                'label' => 'Contract Type',
                'choices' => Offreemploi::CONTRACT_TYPES,
                'attr' => [
                    'class' => 'form-select'
                ],
                'placeholder' => 'Select contract type',
                'constraints' => [
                    new NotBlank(['message' => 'Please select a contract type'])
                ]
            ])
            ->add('emplacement', TextType::class, [
                'label' => 'Location',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Enter job location'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Please enter a location'])
                ]
            ])
            ->add('imageFile', FileType::class, [
                'label' => 'Image',
                'mapped' => true,
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'accept' => 'image/*'
                ],
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/gif',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image file (JPEG, PNG, GIF)',
                    ])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Offreemploi::class,
            'csrf_protection' => true,
            'allow_extra_fields' => true,
        ]);
    }
}