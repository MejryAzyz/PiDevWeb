<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class UserSearchType extends AbstractType
{
    private HttpClientInterface $client;
    
    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $countries = $this->getCountries();

        $builder
            ->add('search', TextType::class, [
                'label' => 'Rechercher',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Nom, prénom, email...',
                    'class' => 'form-control form-control-lg'
                ]
            ])
            ->add('nationalite', ChoiceType::class, [
                'label' => 'Nationalité',
                'required' => false,
                'choices' => $countries,
                'placeholder' => 'Sélectionnez une nationalité',
                'attr' => [
                    'class' => 'form-select form-select-lg'
                ]
            ])
            ->add('status', ChoiceType::class, [
                'label' => 'Statut',
                'required' => false,
                'choices' => [
                    'Tous' => '',
                    'Actif' => 1,
                    'Banni' => 0
                ],
                'attr' => [
                    'class' => 'form-select form-select-lg'
                ]
            ]);
    }

    private function getCountries(): array
    {
        try {
            $response = $this->client->request('GET', 'https://restcountries.com/v3.1/all');
            $countries = $response->toArray();
            
            $choices = ['Toutes' => ''];
            foreach ($countries as $country) {
                $name = $country['name']['common'] ?? '';
                if ($name) {
                    $choices[$name] = $name;
                }
            }
            
            // Sort countries alphabetically
            ksort($choices);
            
            return $choices;
        } catch (\Exception $e) {
            // Fallback to a basic list if API fails
            return [
                'Toutes' => '',
                'Tunisienne' => 'Tunisienne',
                'Française' => 'Française',
                'Algérienne' => 'Algérienne',
                'Marocaine' => 'Marocaine',
                'Autre' => 'Autre'
            ];
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'method' => 'GET',
            'csrf_protection' => false,
        ]);
    }
} 