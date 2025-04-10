<?php

namespace App\Form;

use App\Entity\Docteur;
use App\Entity\PlanningDocteur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlanningDocteurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date_jour', null, [
                'widget' => 'single_text'
            ])
            ->add('heure_debut')
            ->add('heure_fin')
            ->add('docteur', EntityType::class, [
                'class' => Docteur::class,
'choice_label' => 'nom',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PlanningDocteur::class,
        ]);
    }
}
