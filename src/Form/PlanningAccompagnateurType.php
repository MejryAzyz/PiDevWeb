<?php

namespace App\Form;

use App\Entity\Accompagnateur;
use App\Entity\PlanningAccompagnateur;
use App\Entity\Statut;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlanningAccompagnateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date_jour', null, [
                'widget' => 'single_text'
            ])
            ->add('heure_debut')
            ->add('heure_fin')
            ->add('accompagnateur', EntityType::class, [
                'class' => Accompagnateur::class,
'choice_label' => 'username',
            ])
            ->add('statut', EntityType::class, [
                'class' => Statut::class,
'choice_label' => 'type',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PlanningAccompagnateur::class,
        ]);
    }
}
