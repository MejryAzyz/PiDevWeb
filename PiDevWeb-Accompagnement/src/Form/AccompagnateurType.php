<?php

namespace App\Form;

use App\Entity\Accompagnateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccompagnateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('id_accompagnateur')
            ->add('username')
            ->add('password_hash')
            ->add('email')
            ->add('fichier_cv')
            ->add('photo_profil')
            ->add('experience')
            ->add('motivation')
            ->add('langues')
            ->add('statut')
            ->add('date_recrutement', null, [
                'widget' => 'single_text',
            ])
            ->add('date_inscription', null, [
                'widget' => 'single_text',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Accompagnateur::class,
        ]);
    }
}
