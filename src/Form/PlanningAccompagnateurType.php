<?php

namespace App\Form;

use App\Entity\PlanningAccompagnateur;
use App\Entity\Accompagnateur;
use App\Entity\DossierMedical;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlanningAccompagnateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('accompagnateur', EntityType::class, [
                'class' => Accompagnateur::class,
                'choice_label' => 'username',
            ])
            ->add('date_jour', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('heure_debut')
            ->add('heure_fin', TextType::class, [
                'label' => 'Heure de fin',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'HH:mm'
                ]
            ])
            ->add('dossierMedical', EntityType::class, [
                'class' => DossierMedical::class,
                'choice_label' => 'nomPatient',
                'placeholder' => 'Sélectionnez un dossier médical',
                'required' => false,
                'label' => 'Dossier Médical'
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
