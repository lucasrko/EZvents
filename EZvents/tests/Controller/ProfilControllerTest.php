<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class ProfilControllerTest extends WebTestCase
{
    public function testIndex(): void
    {
        $client = static::createClient();
        $em = static::getContainer()->get('doctrine.orm.entity_manager');

        // 1. On crée le faux utilisateur avant de visiter la page
        $pseudo = 'user-' . bin2hex(random_bytes(5));
        $user = new \App\Entity\User();
        $user->setEmail('profil-' . uniqid() . '@ezvents.fr');
        $user->setPassword('pass');
        $user->setFirstname('Test');
        $user->setName('Test');
        $user->setPseudo($pseudo); // <--- Le pseudo qu'on va chercher
        $em->persist($user);
        $em->flush();

        // 2. On charge son profil (maintenant il existe !)
        $client->request('GET', '/profil/' . $pseudo);

        self::assertResponseIsSuccessful();
    }
}
