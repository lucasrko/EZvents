<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'label' => 'Nom de l\'événement',
                'constraints' => [
                    new NotBlank(message: 'Donnez un nom à l\'événement')
                ]
            ])

            ->add('description', null, [
                'label' => 'Description',
                'constraints' => [
                    new NotBlank(message: 'Ajoutez une description')
                ]
            ])

            ->add('adresse', null, [
                'label' => "Adresse de l'Event",
                'attr' => ['class' => 'js-address-input', 'autocomplete' => 'off'],
                'constraints' => [
                    new NotBlank(message: 'Entrez l\'adresse')
                ]
            ])

            ->add('ville', null, [
                'label' => "Ville",
                'attr' => ['class' => 'js-city-input', 'autocomplete' => 'off'],
                'constraints' => [
                    new NotBlank(message: 'Indiquez la ville où se déroule l\'événement.')
                ]
            ])

            ->add('code_postal', null, [
                'label' => "Code Postal",
                'attr' => ['class' => 'js-zipcode-input'],
                'constraints' => [
                    new NotBlank(message: 'Le code postal est requis.'),
                    new Regex(
                        pattern: '/^[0-9]{5}$/',
                        message: 'Un code postal valide comporte exactement 5 chiffres.'
                    )
                ]
            ])

            ->add('categorie', ChoiceType::class, [
                'label' => 'Sélectionnez le jeu',
                'choices' => [
                    'Counter-Strike 2' => 'cs2',
                    'League of Legends' => 'lol',
                    'Valorant' => 'valorant',
                    'Rocket League' => 'rl',
                    'Fortnite' => 'fortnite',
                    'Brawl Stars' => 'bs',
                    'Call Of Duty' => 'cod',
                    'Dota 2' => 'dota 2',
                    'Overwatch' => 'overwatch',
                    'Street Fighter' => 'Street Fighter',
                    'Tekken' => 'tekken',
                    'Team Fight Tactics' => 'tft',
                    'Apex Legends' => 'apex',
                    'Rainbow Six Siege' => 'R6'
                ],
                'constraints' => [
                    new NotBlank(message: 'Sélectionne le jeu de l\'événement')
                ]
            ])

            ->add('date_heure', DateTimeType::class, [
                'label' => 'Date et heure de l\'événement',
                'widget' => 'single_text',
                'constraints' => [
                    new NotBlank(message: 'Fixez une date et l\'heure')
                ]
            ])

            ->add('telephone', TelType::class, [
                'label' => "Numéro de téléphone",
                'attr' => ['placeholder' => '0612345678'],
                'constraints' => [
                    new NotBlank(message: 'Ajoutez un numéro de téléphone'),
                    new Length(
                        min: 10,
                        max: 10,
                        exactMessage: 'Le numéro de téléphone doit comporter exactement 10 chiffres.'
                    ),
                    new Regex(
                        pattern: '/^(?:(?:\+|00)33|0)[1-9](?:[\s.-]*\d{2}){4}$/',
                        message: 'Veuillez entrer un numéro de téléphone valide.'
                    )
                ]
            ])

            ->add('nom_equipe_1', null, [
                'label' => 'Équipe 1 (Optionnel)'
            ])
            ->add('nom_equipe_2', null, [
                'label' => 'Équipe 2 (Optionnel)'
            ])

            ->add('capacite', IntegerType::class, [
                'label' => "Capacité de l'Event",
                'constraints' => [
                    new NotBlank(message: 'Combien de places sont disponibles'),
                    new Positive(message: 'La capacité doit être supérieure à 0 joueur.')
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
