<?php

namespace App\Form;

use App\Entity\Accompagnateur;
use App\Entity\Offreemploi;
use App\Entity\Postulation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostulationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('id_postulation')
            ->add('date_postulation', null, [
                'widget' => 'single_text',
            ])
            ->add('statut')
            ->add('id_accompagnateur', EntityType::class, [
                'class' => Accompagnateur::class,
                'choice_label' => 'id',
            ])
            ->add('id_offre', EntityType::class, [
                'class' => Offreemploi::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Postulation::class,
        ]);
    }
}
