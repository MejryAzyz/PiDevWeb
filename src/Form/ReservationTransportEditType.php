<?php

namespace App\Form;

use App\Entity\Reservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class ReservationTransportEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateDepart', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de départ',
                'constraints' => [
                    new NotBlank([
                        'message' => 'La date de départ est obligatoire.',
                    ]),
                    new GreaterThan([
                        'value' => 'today',
                        'message' => 'La date de départ doit être supérieure à aujourd\'hui.',
                    ])
                ]
            ])
            ->add('heureDepart', TextType::class, [
                'label' => 'Heure de départ',
                'attr' => [
                    'placeholder' => 'HH:MM',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'L\'heure de départ est obligatoire.',
                    ]),
                    new Regex([
                        'pattern' => '/^([01][0-9]|2[0-3]):[0-5][0-9]$/',
                        'message' => 'Veuillez entrer une heure valide au format HH:MM.',
                    ])
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
