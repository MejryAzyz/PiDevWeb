<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('search', TextType::class, [
                'label' => 'Rechercher',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Nom, prénom, email...',
                    'class' => 'form-control'
                ]
            ])
            ->add('nationalite', ChoiceType::class, [
                'label' => 'Nationalité',
                'required' => false,
                'choices' => [
                    'Toutes' => '',
                    'Tunisienne' => 'Tunisienne',
                    'Française' => 'Française',
                    'Algérienne' => 'Algérienne',
                    'Marocaine' => 'Marocaine',
                    'Autre' => 'Autre'
                ],
                'attr' => [
                    'class' => 'form-select'
                ]
            ])
            ->add('status', ChoiceType::class, [
                'label' => 'Statut',
                'required' => false,
                'choices' => [
                    'Tous' => '',
                    'Actif' => 1,
                    'Banni' => 0
                ],
                'attr' => [
                    'class' => 'form-select'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'method' => 'GET',
            'csrf_protection' => false,
        ]);
    }
} 