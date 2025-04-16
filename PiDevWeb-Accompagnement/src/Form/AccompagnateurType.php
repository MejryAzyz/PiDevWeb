<?php

namespace App\Form;

use App\Entity\Accompagnateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\File;

class AccompagnateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'label' => 'Full Name',
                'constraints' => [
                    new NotBlank(['message' => 'Please enter your full name']),
                    new Length(['min' => 2, 'max' => 100])
                ],
                'attr' => ['placeholder' => 'Enter your full name']
            ])
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Please enter your email']),
                    new Email(['message' => 'Please enter a valid email address'])
                ],
                'attr' => ['placeholder' => 'Enter your email address']
            ])
            ->add('password_hash', PasswordType::class, [
                'label' => 'Password',
                'constraints' => [
                    new NotBlank(['message' => 'Please enter a password']),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters'
                    ])
                ],
                'attr' => ['placeholder' => 'Create a password']
            ])
            ->add('fichier_cv', FileType::class, [
                'label' => 'CV (PDF file)',
                'mapped' => true,
                'required' => true,
                'constraints' => [
                    new File([
                        'maxSize' => '2048k',
                        'mimeTypes' => ['application/pdf'],
                        'mimeTypesMessage' => 'Please upload a valid PDF document',
                    ])
                ],
                'attr' => ['accept' => '.pdf']
            ])
            ->add('photo_profil', FileType::class, [
                'label' => 'Profile Photo',
                'mapped' => true,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => ['image/jpeg', 'image/png'],
                        'mimeTypesMessage' => 'Please upload a valid image (JPEG, PNG)',
                    ])
                ],
                'attr' => ['accept' => 'image/*']
            ])
            ->add('experience', TextareaType::class, [
                'label' => 'Professional Experience',
                'constraints' => [new NotBlank()],
                'attr' => [
                    'rows' => 5,
                    'placeholder' => 'Describe your relevant work experience'
                ]
            ])
            ->add('motivation', TextareaType::class, [
                'label' => 'Motivation Letter',
                'constraints' => [new NotBlank()],
                'attr' => [
                    'rows' => 5,
                    'placeholder' => 'Why are you interested in this position?'
                ]
            ])
            ->add('langues', ChoiceType::class, [
                'label' => 'Languages',
                'multiple' => true,
                'expanded' => true,
                'choices' => [
                    'English' => 'English',
                    'French' => 'French',
                    'Arabic' => 'Arabic',
                    'Spanish' => 'Spanish',
                    'German' => 'German'
                ],
                'constraints' => [new NotBlank()]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Accompagnateur::class,
        ]);
    }
}
