<?php

namespace App\Form;

use App\Entity\Service;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ServiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('wifi', CheckboxType::class, [
                'label' => 'Wi-Fi',
                'required' => false,
            ])
            ->add('climatisation', CheckboxType::class, [
                'label' => 'Climatisation',
                'required' => false,
            ])
            ->add('menage_quotidien', CheckboxType::class, [
                'label' => 'Ménage quotidien',
                'required' => false,
            ])
            ->add('conciergerie', CheckboxType::class, [
                'label' => 'Service de conciergerie',
                'required' => false,
            ])
            ->add('linge_lit', CheckboxType::class, [
                'label' => 'Linge de lit',
                'required' => false,
            ])
            ->add('salle_bain_privee', CheckboxType::class, [
                'label' => 'Salle de bain privée',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Service::class,
        ]);
    }
} 