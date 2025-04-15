<?php

namespace App\Form;

use App\Entity\Paiement;
use App\Entity\Reservation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Regex;

class PaiementType extends AbstractType
{


    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('montant', TextType::class, [
                'constraints' => [
                    new Regex([
                        'pattern' => '/^(?!0(\.0+)?$)(\d+(\.\d+)?){1}$/', // Regex to ensure a positive number
                        'message' => 'Le montant doit être un nombre positif supérieur à 0.',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Paiement::class,
        ]);
    }
}
