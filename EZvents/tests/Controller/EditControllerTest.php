<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class EditControllerTest extends WebTestCase
{
    public function testIndex(): void
    {
        $client = static::createClient();
        $em = static::getContainer()->get('doctrine.orm.entity_manager');

        // 1. On crée un utilisateur factice
        $user = new \App\Entity\User();
        $user->setEmail('admin-edit-' . uniqid() . '@ezvents.fr');
        $user->setPassword('password');
        $user->setName('Dupont');
        $user->setFirstname('Jean');
        $user->setPseudo('edit-' . bin2hex(random_bytes(5)));
        $em->persist($user);

        // 2. On crée l'événement factice
        $event = new \App\Entity\Event();
        $event->setName('Test Event');
        $event->setAdresse('123 rue de la Victoire');
        $event->setVille('Paris');
        $event->setCodePostal('75000');
        $event->setCapacite(100);
        $event->setDateHeure(new \DateTime('+1 day'));
        $event->setCategorie('Esport');
        $event->setTelephone('0600000000');

        // ---> LA LIGNE MAGIQUE EST ICI <---
        // On relie l'événement à l'utilisateur qu'on vient de créer
        $event->setOrganisateur($user);

        $em->persist($event);
        $em->flush();

        // 3. On connecte l'utilisateur
        $client->loginUser($user);

        // 4. On se rend sur la page protégée
        $client->request('GET', '/edit/modifier/' . $event->getId());

        self::assertResponseIsSuccessful();
    }
}
