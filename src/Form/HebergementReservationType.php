<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Reservation;
use Symfony\Component\Validator\Constraints as Assert;

class HebergementReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateDebut', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de début',
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\GreaterThan([
                        'value' => 'today',
                        'message' => 'La date de début doit être supérieure à aujourd\'hui.',
                    ]),
                ],
            ])
            ->add('dateFin', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de fin',
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Callback(function ($dateFin, $context) {
                        $form = $context->getRoot();
                        $dateDebut = $form->get('dateDebut')->getData();

                        if ($dateDebut && $dateFin <= $dateDebut) {
                            $context->buildViolation('La date de fin doit être après la date de début.')
                                ->addViolation();
                        }
                    }),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
