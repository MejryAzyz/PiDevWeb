<?php

namespace App\Form;

use App\Entity\Clinique;
use App\Entity\Docteur;
use App\Entity\Specialite;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DocteurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('telephone')
            ->add('email')
<<<<<<< HEAD
            ->add('idClinique', EntityType::class, [
                'class' => Clinique::class,
                'choice_label' => 'id',
            ])
            ->add('idSpecialite', EntityType::class, [
                'class' => Specialite::class,
                'choice_label' => 'id',
=======
            ->add('clinique', EntityType::class, [
                'class' => Clinique::class,
                // 'choice_label' => 'id',
                'choice_label' => 'nom', // Affiche le nom de la clinique dans le dropdown
                'placeholder' => 'Sélectionner une clinique',
            ])
            ->add('specialite', EntityType::class, [
                'class' => Specialite::class,
                // 'choice_label' => 'id',
                'choice_label' => 'nom', // Affiche le nom de la spécialité dans le dropdown
                'placeholder' => 'Sélectionner une spécialité',
>>>>>>> c4098f6 (bundle)
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Docteur::class,
        ]);
    }
}
