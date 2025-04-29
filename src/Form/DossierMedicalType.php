<?php

namespace App\Form;

use App\Entity\DossierMedical;
use App\Entity\PlanningDocteur;
use App\Entity\PlanningAccompagnateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DossierMedicalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomPatient', TextType::class, [
                'label' => 'Nom du patient',
                'attr' => ['placeholder' => 'Entrez le nom du patient']
            ])
            ->add('testsMedicaux', TextareaType::class, [
                'label' => 'Tests médicaux',
                'required' => false,
                'attr' => ['placeholder' => 'Entrez les tests médicaux', 'rows' => 4]
            ])
            ->add('antecedents', TextareaType::class, [
                'label' => 'Antécédents',
                'required' => false,
                'attr' => ['placeholder' => 'Entrez les antécédents', 'rows' => 4]
            ])
            ->add('allergies', TextareaType::class, [
                'label' => 'Allergies',
                'required' => false,
                'attr' => ['placeholder' => 'Entrez les allergies', 'rows' => 4]
            ])
            ->add('restrictionsAlimentaires', TextareaType::class, [
                'label' => 'Restrictions alimentaires',
                'required' => false,
                'attr' => ['placeholder' => 'Entrez les restrictions alimentaires', 'rows' => 4]
            ])
            ->add('planningDocteurs', EntityType::class, [
                'class' => PlanningDocteur::class,
                'choice_label' => 'idPlanning',
                'required' => false,
                'multiple' => true,
                'placeholder' => 'Sélectionnez un planning docteur'
            ])
            ->add('planningAccompagnateurs', EntityType::class, [
                'class' => PlanningAccompagnateur::class,
                'choice_label' => 'idPlanning',
                'required' => false,
                'multiple' => true,
                'placeholder' => 'Sélectionnez un planning accompagnateur'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DossierMedical::class,
        ]);
    }
} 