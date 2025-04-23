<?php

namespace App\Form;

use App\Entity\Hebergement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
<<<<<<< HEAD
=======
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
>>>>>>> c4098f6 (bundle)

class HebergementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
<<<<<<< HEAD
            ->add('nom')
            ->add('adresse')
            ->add('telephone')
            ->add('email')
            ->add('capacite')
            ->add('tarif_nuit')
            ->add('image_url')
=======
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Entrez le nom'
                ]
            ])
            ->add('rue', TextType::class, [
                'label' => 'Rue',
                'required' => true,
                'mapped' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Entrez l\'adresse'
                ]
            ])
            ->add('ville', TextType::class, [
                'label' => 'Ville',
                'required' => true,
                'mapped' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Entrez la ville'
                ]
            ])
            ->add('pays', TextType::class, [
                'label' => 'Pays',
                'required' => true,
                'mapped' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Le pays sera rempli automatiquement',
                    'readonly' => true
                ]
            ])
            ->add('adresse', HiddenType::class, [
                'required' => true,
            ])
            ->add('telephone', TextType::class, [
                'label' => 'Téléphone',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Entrez le numéro de téléphone'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Entrez l\'adresse email'
                ]
            ])
            ->add('capacite', IntegerType::class, [
                'label' => 'Capacité',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Entrez la capacité'
                ]
            ])
            ->add('tarif_nuit', NumberType::class, [
                'label' => 'Tarif par nuit',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Entrez le tarif par nuit (DT)',
                    'min' => '0',
                    'step' => '0.001'
                ]
            ])
            ->add('image_url', HiddenType::class, [
                'required' => false,
            ])
            ->add('service', ServiceType::class, [
                'label' => false,
                'required' => false,
            ])
>>>>>>> c4098f6 (bundle)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Hebergement::class,
        ]);
    }
}