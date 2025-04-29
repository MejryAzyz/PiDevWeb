<?php

namespace App\Form;

use App\Entity\Accompagnateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccompagnateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'label' => 'Nom d\'utilisateur',
                'attr' => ['class' => 'form-control pill', 'placeholder' => 'Nom d\'utilisateur'],
            ])
            ->add('password_hash', PasswordType::class, [
                'label' => 'Mot de passe',
                'attr' => ['class' => 'form-control pill', 'placeholder' => 'Mot de passe'],
                'mapped' => false, // Handled manually in controller
            ])
            ->add('email', TextType::class, [
                'label' => 'Email',
                'attr' => ['class' => 'form-control pill', 'placeholder' => 'Email'],
            ])
            ->add('fichier_cv', FileType::class, [
                'label' => 'CV',
                'attr' => ['class' => 'form-control pill'],
                'mapped' => false,
                'required' => false,
            ])
            ->add('photo_profil', FileType::class, [
                'label' => 'Photo de profil',
                'attr' => ['class' => 'form-control pill'],
                'mapped' => false,
                'required' => false,
            ])
            ->add('experience', TextareaType::class, [
                'label' => 'Expérience',
                'attr' => ['class' => 'form-control pill', 'placeholder' => 'Expérience'],
            ])
            ->add('motivation', TextareaType::class, [
                'label' => 'Motivation',
                'attr' => ['class' => 'form-control pill', 'placeholder' => 'Motivation'],
            ])
            ->add('langues', TextType::class, [
                'label' => 'Langues',
                'attr' => ['class' => 'form-control pill', 'placeholder' => 'Langues'],
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
