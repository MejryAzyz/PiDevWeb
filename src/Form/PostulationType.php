<?php

namespace App\Form;

use App\Entity\Accompagnateur;
use App\Entity\Offreemploi;
use App\Entity\Postulation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostulationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date_postulation', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Date of Postulation',
                'required' => true,
            ])
            ->add('statut', ChoiceType::class, [
                'choices' => [
                    'Pending' => 'Pending',
                    'Accepted' => 'Accepted',
                    'Rejected' => 'Rejected',
                ],
                'label' => 'Status',
                'required' => true,
            ])
            ->add('id_accompagnateur', EntityType::class, [
                'class' => Accompagnateur::class,
                'choice_label' => 'username',
                'label' => 'Accompagnateur',
                'required' => true,
            ])
            ->add('id_offre', EntityType::class, [
                'class' => Offreemploi::class,
                'choice_label' => 'titre',
                'label' => 'Job Offer',
                'required' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Postulation::class,
        ]);
    }
}
