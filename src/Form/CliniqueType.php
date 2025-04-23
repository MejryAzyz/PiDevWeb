<?php

namespace App\Form;

use App\Entity\Clinique;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
<<<<<<< HEAD
=======
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
>>>>>>> c4098f6 (bundle)

class CliniqueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
<<<<<<< HEAD
            ->add('nom')
            ->add('adresse')
            ->add('telephone')
            ->add('email')
            ->add('rate')
            ->add('description')
            ->add('prix')
        ;
=======
        ->add('nom', TextType::class, [
            'label' => 'Nom de la Clinique',
            'required' => true,
            'attr' => ['placeholder' => 'Entrez le nom de la clinique']
        ])
        // Champ pour l'adresse
        ->add('adresse', TextareaType::class, [
            'label' => 'Adresse',
            'required' => true,
            'attr' => ['placeholder' => 'Entrez l’adresse complète']
        ])
        // Champ pour le numéro de téléphone
        ->add('telephone', TextType::class, [
            'label' => 'Numéro de Téléphone',
            'required' => true,
            'attr' => ['placeholder' => 'Entrez le numéro de téléphone']
        ])
        // Champ pour l'email
        ->add('email', EmailType::class, [
            'label' => 'Adresse Email',
            'required' => true,
            'attr' => ['placeholder' => 'Entrez l’adresse email']
        ])
        
        // Champ pour la description de la clinique
        ->add('description', TextareaType::class, [
            'label' => 'Description',
            'required' => false,
            'attr' => ['placeholder' => 'Décrivez la clinique']
        ])
        // Champ pour le prix
        ->add('prix', NumberType::class, [
            'label' => 'Prix (en €)',
            'required' => true,
            'attr' => ['placeholder' => 'Entrez le prix en euros']
        ]);
>>>>>>> c4098f6 (bundle)
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Clinique::class,
        ]);
    }
}
