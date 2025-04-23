<?php

namespace App\Form;

use App\Entity\Role;
use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class UtilisateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('email')
            ->add('mot_de_passe')
            ->add('telephone')
            ->add('date_naissance', DateType::class, [
                'widget' => 'single_text', // Use a single text input (e.g., <input type="date">)
                'required' => false, // Do not make the field required at the HTML level
                'empty_data' => null, // Explicitly set empty data to null
                'label' => 'Date de naissance',
            ])
            ->add('adresse')
            ->add('nationalite')
          
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
