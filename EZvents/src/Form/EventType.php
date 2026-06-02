<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\user;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',null,['label'=>'Nom'])
            ->add('description')
            ->add('adresse',null,['label'=>"Adresse de l'Event"])
            ->add('categorie', ChoiceType::class, [
                'label' => 'Selectionnez le jeu',
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
                    'Raimbow Six Siege' => 'R6'
                ]
            ])
            ->add('date_heure')
            ->add('telephone', TelType::class, [
                'label' => "Numéro de téléphone",
                'attr' => ['placeholder' => '0612345678'], // 🚀 Ligne 45 nettoyée
                'constraints' => [
                    new Length(
                        min: 10,
                        max: 10,
                        exactMessage: 'Le numéro de téléphone doit comporter exactement {{ limit }} chiffres.'
                    ),
                    new Regex(
                        pattern: '/^(?:(?:\+|00)33|0)[1-9](?:[\s.-]*\d{2}){4}$/', // 🚀 Ligne 48 corrigée avec l'argument nommé
                        message: 'Veuillez entrer un numéro de téléphone valide.'
                    )
                ]
            ])
            ->add('nom_equipe_1')
            ->add('nom_equipe_2')
            ->add('capacite',null,['label'=>"Capacité de l'Event"])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
