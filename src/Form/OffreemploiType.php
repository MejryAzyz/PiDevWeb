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
use Symfony\Component\Validator\Constraints as Assert;

class OffreemploiType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Enter job title'
                ],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Title is required'
                    ]),
                    new Assert\Length([
                        'max' => 255,
                        'maxMessage' => 'Title cannot be longer than {{ limit }} characters'
                    ])
                ]
            ])
            ->add('description', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 4,
                    'placeholder' => 'Enter job description'
                ],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Description is required'
                    ])
                ]
            ])
            ->add('typeposte', ChoiceType::class, [
                'choices' => [
                    'Full Time' => 'Full Time',
                    'Part Time' => 'Part Time',
                    'Contract' => 'Contract',
                    'Internship' => 'Internship',
                    'Temporary' => 'Temporary'
                ],
                'attr' => [
                    'class' => 'form-select'
                ],
                'placeholder' => 'Select job type',
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Job type is required'
                    ])
                ]
            ])
            ->add('typecontrat', ChoiceType::class, [
                'choices' => [
                    'CDI' => 'CDI',
                    'CDD' => 'CDD',
                    'Freelance' => 'Freelance',
                    'Internship' => 'Internship',
                    'Apprenticeship' => 'Apprenticeship'
                ],
                'attr' => [
                    'class' => 'form-select'
                ],
                'placeholder' => 'Select contract type',
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Contract type is required'
                    ])
                ]
            ])
            ->add('emplacement', ChoiceType::class, [
                'choices' => [
                    'France' => 'France',
                    'Belgique' => 'Belgique',
                    'Suisse' => 'Suisse',
                    'Luxembourg' => 'Luxembourg',
                    'Remote' => 'Remote'
                ],
                'attr' => [
                    'class' => 'form-select'
                ],
                'placeholder' => 'Select location',
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Location is required'
                    ])
                ]
            ])
            ->add('etat', ChoiceType::class, [
                'choices' => [
                    'Active' => 'active',
                    'Inactive' => 'inactive'
                ],
                'attr' => [
                    'class' => 'form-select'
                ],
                'data' => 'active',
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Status is required'
                    ])
                ]
            ])
            ->add('imageurl', FileType::class, [
                'required' => false,
                'mapped' => false,
                'attr' => [
                    'class' => 'form-control',
                    'accept' => 'image/jpeg,image/png'
                ],
                'constraints' => [
                    new Assert\File([
                        'maxSize' => '2M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png'
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image (JPEG or PNG)',
                        'maxSizeMessage' => 'The file is too large ({{ size }} {{ suffix }}). Maximum size is {{ limit }} {{ suffix }}'
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
            'csrf_field_name' => '_token',
            'csrf_token_id' => 'offreemploi_form'
        ]);
    }
}