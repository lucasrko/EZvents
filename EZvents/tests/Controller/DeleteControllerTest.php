<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class DeleteControllerTest extends WebTestCase
{
    public function testIndex(): void
    {
        $client = static::createClient();
        $em = static::getContainer()->get('doctrine.orm.entity_manager');

        // 1. On crée un utilisateur factice
        $user = new \App\Entity\User();
        $user->setEmail('admin-delete-' . uniqid() . '@ezvents.fr');
        $user->setPassword('password');
        $user->setName('Dupont');
        $user->setFirstname('Jean');
        $user->setPseudo('del-' . bin2hex(random_bytes(5)));
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

        // On relie l'événement à l'utilisateur qu'on vient de créer
        $event->setOrganisateur($user);

        $em->persist($event);
        $em->flush();

        // 3. On connecte l'utilisateur
        $client->loginUser($user);

        // 4. On demande la suppression via une requête POST
        $client->request('POST', '/event/delete/' . $event->getId());
        
        self::assertResponseRedirects('/profil/' . $user->getPseudo());

        // On vérifie que l'événement a bien été archivé (et non supprimé physiquement)
        $em->clear();
        $archivedEvent = $em->getRepository(\App\Entity\Event::class)->find($event->getId());
        self::assertNotNull($archivedEvent);
        self::assertTrue($archivedEvent->isArchived());
    }
}
