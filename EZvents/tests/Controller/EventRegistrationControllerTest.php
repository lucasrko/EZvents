<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class EventRegistrationControllerTest extends WebTestCase
{
    public function testIndex(): void
    {
        $client = static::createClient();
        $em = static::getContainer()->get('doctrine.orm.entity_manager');

        // 1. On crée un utilisateur factice
        $user = new \App\Entity\User();
        $user->setEmail('participant-' . uniqid() . '@ezvents.fr');
        $user->setPassword('password');
        $user->setName('Dupont');
        $user->setFirstname('Jean');
        $user->setPseudo('part-' . bin2hex(random_bytes(5)));
        $em->persist($user);

        // 2. On crée l'événement factice
        $event = new \App\Entity\Event();
        $event->setName('Tournoi Test');
        $event->setAdresse('123 rue de la Victoire');
        $event->setVille('Paris');
        $event->setCodePostal('75000');
        $event->setCapacite(100);
        $event->setDateHeure(new \DateTime('+1 day'));
        $event->setCategorie('valo');
        $event->setTelephone('0600000000');

        $em->persist($event);
        $em->flush();

        // 3. On connecte l'utilisateur
        $client->loginUser($user);

        // 4. On demande l'inscription
        $client->request('POST', '/event/' . $event->getId() . '/inscription');

        self::assertResponseRedirects('/event/' . $event->getId());
    }
}
