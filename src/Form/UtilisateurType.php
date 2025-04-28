<?php

namespace App\Form;

use App\Entity\Role;
use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UtilisateurType extends AbstractType
{
    private HttpClientInterface $client;
    
    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('email')
            ->add('mot_de_passe')
            ->add('telephone')
            ->add('date_naissance', DateType::class, [
                'widget' => 'single_text', // Use a single text input (e.g., <input type="date">)
                'required' => false, // Do not make the field required at the HTML level
                'empty_data' => null, // Explicitly set empty data to null
                'label' => 'Date de naissance',
            ])
            ->add('adresse')
            ->add('nationalite', ChoiceType::class, [
                'choices' => $this->getNationalities(),
                'placeholder' => 'Choisissez votre nationalité',
                'attr' => ['class' => 'form-control'],
            ])
        ;
    }

    private function getNationalities(): array
    {
        $response = $this->client->request('GET', 'https://restcountries.com/v3.1/all');
        $countries = $response->toArray();

        $choices = [];
        foreach ($countries as $country) {
            if (isset($country['translations']['fra']['common'])) {
                $name = $country['translations']['fra']['common']; // Nom en français
                $choices[$name] = $name;
            } elseif (isset($country['name']['common'])) {
                $name = $country['name']['common'];
                $choices[$name] = $name;
            }
        }

        asort($choices); // Trie alphabétique
        return $choices;
    
               
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
