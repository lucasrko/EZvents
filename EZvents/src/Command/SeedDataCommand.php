<?php

namespace App\Command;

use App\Entity\Event;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:seed-data',
    description: 'Génère des utilisateurs et au moins un événement pour chaque catégorie de jeu.',
)]
class SeedDataCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Génération des données de test (EZvents - Un événement par jeu)');

        // 1. Liste des utilisateurs à créer
        $userData = [
            [
                'email' => 'slayer99@ezvents.fr',
                'pseudo' => 'Slayer99',
                'firstname' => 'Alex',
                'name' => 'Dupont',
                'tel' => '0612345678'
            ],
            [
                'email' => 'pandagirl@ezvents.fr',
                'pseudo' => 'PandaGirl',
                'firstname' => 'Emma',
                'name' => 'Martin',
                'tel' => '0623456789'
            ],
            [
                'email' => 'zeus@ezvents.fr',
                'pseudo' => 'Zeus',
                'firstname' => 'Jean',
                'name' => 'Bernard',
                'tel' => '0634567890'
            ],
            [
                'email' => 'shadow@ezvents.fr',
                'pseudo' => 'Shadow',
                'firstname' => 'Lucas',
                'name' => 'Dubois',
                'tel' => '0645678901'
            ],
            [
                'email' => 'valkyrie@ezvents.fr',
                'pseudo' => 'Valkyrie',
                'firstname' => 'Chloe',
                'name' => 'Thomas',
                'tel' => '0656789012'
            ]
        ];

        $users = [];
        foreach ($userData as $data) {
            $existingUser = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $data['email']]);
            if ($existingUser) {
                $users[] = $existingUser;
                continue;
            }

            $user = new User();
            $user->setEmail($data['email']);
            $user->setPseudo($data['pseudo']);
            $user->setFirstName($data['firstname']);
            $user->setName($data['name']);
            $user->setTelephone($data['tel']);
            
            $hashedPassword = $this->passwordHasher->hashPassword($user, 'password123');
            $user->setPassword($hashedPassword);

            $this->entityManager->persist($user);
            $users[] = $user;
            $io->text(sprintf('Création de l\'utilisateur : %s (@%s)', $data['email'], $data['pseudo']));
        }

        $this->entityManager->flush();

        // 2. Liste des 14 événements (un pour chaque jeu)
        $eventData = [
            [
                'name' => 'Showmatch Valorant Cup',
                'categorie' => 'valorant',
                'adresse' => '15 Boulevard Voltaire',
                'ville' => 'Paris',
                'code_postal' => '75011',
                'capacite' => 50,
                'date_offset' => '+5 days',
                'tel' => '0611223344',
                'description' => "Un showmatch d'exception entre des joueurs professionnels de la scène Valorant. Venez nombreux encourager vos équipes !",
                'organizer_idx' => 0, // Slayer99
            ],
            [
                'name' => 'Tournoi League of Legends 5v5',
                'categorie' => 'lol',
                'adresse' => '45 Rue de la République',
                'ville' => 'Lyon',
                'code_postal' => '69002',
                'capacite' => 80,
                'date_offset' => '+10 days',
                'tel' => '0622334455',
                'description' => "Rejoignez-nous pour ce tournoi amateur 5v5. De nombreuses récompenses à la clé et un stream en direct de la finale !",
                'organizer_idx' => 1, // PandaGirl
            ],
            [
                'name' => 'Ligue Counter-Strike 2 Paris',
                'categorie' => 'cs2',
                'adresse' => '8 Rue de la Canebière',
                'ville' => 'Marseille',
                'code_postal' => '13001',
                'capacite' => 40,
                'date_offset' => '+15 days',
                'tel' => '0633445566',
                'description' => "La compétition régionale CS2 commence ici. Formez vos équipes de 5 joueurs et venez affronter les meilleurs de la région.",
                'organizer_idx' => 2, // Zeus
            ],
            [
                'name' => 'Solo Cash Cup Fortnite',
                'categorie' => 'fortnite',
                'adresse' => '12 Grand Rue',
                'ville' => 'Strasbourg',
                'code_postal' => '67000',
                'capacite' => 100,
                'date_offset' => '+3 days',
                'tel' => '0644556677',
                'description' => "Solo Cash Cup Fortnite ouverte à tous les joueurs de la région. Venez prouver votre valeur et tentez de décrocher le cashprize !",
                'organizer_idx' => 3, // Shadow
            ],
            [
                'name' => 'Brawl Stars Major Cup',
                'categorie' => 'bs',
                'adresse' => '5 Rue de la Trinité',
                'ville' => 'Toulouse',
                'code_postal' => '31000',
                'capacite' => 60,
                'date_offset' => '+8 days',
                'tel' => '0655667788',
                'description' => "Venez participer à la Brawl Stars Major Cup de Toulouse. Matchs en 3v3 en direct avec des animations sur place.",
                'organizer_idx' => 4, // Valkyrie
            ],
            [
                'name' => 'Rocket League Championship Cup',
                'categorie' => 'rl',
                'adresse' => '22 Rue des Docks',
                'ville' => 'Nantes',
                'code_postal' => '44000',
                'capacite' => 32,
                'date_offset' => '+12 days',
                'tel' => '0677889900',
                'description' => "Tournoi Rocket League en 2v2 (16 équipes max). Venez tenter de marquer les plus beaux buts de la saison !",
                'organizer_idx' => 3, // Shadow
            ],
            [
                'name' => 'Call of Duty Warzone Arena',
                'categorie' => 'cod',
                'adresse' => '104 Rue du Faubourg Saint-Antoine',
                'ville' => 'Paris',
                'code_postal' => '75012',
                'capacite' => 75,
                'date_offset' => '+6 days',
                'tel' => '0688990011',
                'description' => "Affrontement en Trio sur Warzone. Survivez jusqu'à la fin pour décrocher la victoire royale et le cash prize !",
                'organizer_idx' => 0, // Slayer99
            ],
            [
                'name' => 'Dota 2 International Qualifier',
                'categorie' => 'dota 2',
                'adresse' => '17 Rue de Bourgogne',
                'ville' => 'Orléans',
                'code_postal' => '45000',
                'capacite' => 50,
                'date_offset' => '+20 days',
                'tel' => '0699001122',
                'description' => "Tournoi qualificatif Dota 2 régional. Matchs intenses à prévoir sur la carte du dota.",
                'organizer_idx' => 1, // PandaGirl
            ],
            [
                'name' => 'Overwatch 2 Open Division',
                'categorie' => 'overwatch',
                'adresse' => '9 Rue de l\'Écuyer',
                'ville' => 'Lille',
                'code_postal' => '59000',
                'capacite' => 60,
                'date_offset' => '+14 days',
                'tel' => '0600112233',
                'description' => "Tournoi par équipe de 5 sur Overwatch 2. Venez composer votre meilleure équipe de tank, DPS et healers !",
                'organizer_idx' => 2, // Zeus
            ],
            [
                'name' => 'Street Fighter 6 Ultimate Fight',
                'categorie' => 'Street Fighter',
                'adresse' => '88 Rue de l\'Amiral Courbet',
                'ville' => 'Amiens',
                'code_postal' => '80000',
                'capacite' => 32,
                'date_offset' => '+4 days',
                'tel' => '0611224455',
                'description' => "Tournoi solo à élimination directe sur Street Fighter 6. Qui sera le roi du Hadoken ?",
                'organizer_idx' => 3, // Shadow
            ],
            [
                'name' => 'Tekken 8 Iron Fist Tournament',
                'categorie' => 'tekken',
                'adresse' => '14 Cours Victor Hugo',
                'ville' => 'Bordeaux',
                'code_postal' => '33000',
                'capacite' => 32,
                'date_offset' => '+7 days',
                'tel' => '0622335566',
                'description' => "Préparez vos combos pour ce tournoi Tekken 8 sur grand écran. De nombreux lots à gagner !",
                'organizer_idx' => 4, // Valkyrie
            ],
            [
                'name' => 'TFT Tactician Crown Paris',
                'categorie' => 'tft',
                'adresse' => '44 Rue du Louvre',
                'ville' => 'Paris',
                'code_postal' => '75001',
                'capacite' => 64,
                'date_offset' => '+9 days',
                'tel' => '0633446677',
                'description' => "Trouvez les meilleures compositions d'équipe et gérez votre économie pour remporter la couronne de tacticien TFT !",
                'organizer_idx' => 0, // Slayer99
            ],
            [
                'name' => 'Apex Legends Global Series Local',
                'categorie' => 'apex',
                'adresse' => '1 Place de la Comédie',
                'ville' => 'Montpellier',
                'code_postal' => '34000',
                'capacite' => 60,
                'date_offset' => '+11 days',
                'tel' => '0644557788',
                'description' => "Tournoi Apex Legends par équipe de trois. Choisissez vos légendes et survivez à l'arène !",
                'organizer_idx' => 1, // PandaGirl
            ],
            [
                'name' => 'R6 Siege Challenger Cup',
                'categorie' => 'R6',
                'adresse' => '11 Rue Jeanne d\'Arc',
                'ville' => 'Rouen',
                'code_postal' => '76000',
                'capacite' => 40,
                'date_offset' => '+16 days',
                'tel' => '0655668899',
                'description' => "Tournoi Rainbow Six Siege en 5v5 tactique. Coopération et stratégie de défense/attaque requises !",
                'organizer_idx' => 2, // Zeus
            ]
        ];

        $events = [];
        foreach ($eventData as $data) {
            $existingEvent = $this->entityManager->getRepository(Event::class)->findOneBy(['name' => $data['name']]);
            if ($existingEvent) {
                $events[] = $existingEvent;
                continue;
            }

            $event = new Event();
            $event->setName($data['name']);
            $event->setCategorie($data['categorie']);
            $event->setAdresse($data['adresse']);
            $event->setVille($data['ville']);
            $event->setCodePostal($data['code_postal']);
            $event->setCapacite($data['capacite']);
            $event->setDateHeure(new \DateTime($data['date_offset']));
            $event->setTelephone($data['tel']);
            $event->setDescription($data['description']);
            
            $organizer = $users[$data['organizer_idx']];
            $event->setOrganisateur($organizer);

            $this->entityManager->persist($event);
            $events[] = $event;
            $io->text(sprintf('Création de l\'événement : "%s" (%s) par @%s', $data['name'], $data['categorie'], $organizer->getPseudo()));
        }

        $this->entityManager->flush();

        // 3. Inscription de participants aux événements de manière cyclique
        $io->section('Inscription des participants aux événements...');
        foreach ($events as $idx => $event) {
            for ($i = 1; $i <= 3; $i++) {
                $participantIdx = ($eventData[$idx]['organizer_idx'] + $i) % count($users);
                $participant = $users[$participantIdx];
                
                if (!$event->getParticipants()->contains($participant)) {
                    $event->addParticipant($participant);
                    $io->text(sprintf('  - @%s s\'inscrit à "%s"', $participant->getPseudo(), $event->getName()));
                }
            }
        }

        $this->entityManager->flush();

        $io->success('Génération des données de test terminée avec succès !');
        $io->text([
            'Informations de connexion générées (mot de passe identique pour tous : password123) :',
            '  1. slayer99@ezvents.fr',
            '  2. pandagirl@ezvents.fr',
            '  3. zeus@ezvents.fr',
            '  4. shadow@ezvents.fr',
            '  5. valkyrie@ezvents.fr',
        ]);

        return Command::SUCCESS;
    }
}
