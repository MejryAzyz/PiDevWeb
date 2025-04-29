<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('nom', TextType::class, [
            //     'label' => 'Nom',
            //     'constraints' => [
            //         new NotBlank(['message' => 'Le nom est requis']),
            //     ],
            // ])
            // ->add('prenom', TextType::class, [
            //     'label' => 'Prénom',
            //     'constraints' => [
            //         new NotBlank(['message' => 'Le prénom est requis']),
            //     ],
            // ])
            // ->add('email', EmailType::class, [
            //     'label' => 'Email',
            //     'constraints' => [
            //         new NotBlank(['message' => 'L\'email est requis']),
            //         new Email(['message' => 'L\'email n\'est pas valide']),
            //     ],
            // ])
            // ->add('telephone', TextType::class, [
            //     'label' => 'Téléphone',
            //     'required' => false,
            // ])
            // ->add('adresse', TextType::class, [
            //     'label' => 'Adresse',
            //     'required' => false,
            // ]);
            ->add('nom')
            ->add('prenom')
            ->add('email')
            ->add('telephone')
           
            ->add('adresse')
            
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
} 