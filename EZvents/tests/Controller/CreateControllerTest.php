<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\UserRepository;

final class CreateControllerTest extends WebTestCase
{
    public function testIndex(): void
    {
        $client = static::createClient();
        $container = static::getContainer();
        $em = $container->get('doctrine.orm.entity_manager');

        $user = new \App\Entity\User();
        $user->setEmail('create-test-' . uniqid() . '@ezvents.fr');
        $user->setPassword('password');
        $user->setFirstname('Test');
        $user->setname('Test');
        $user->setPseudo('user-' . bin2hex(random_bytes(5)));

        $em->persist($user);
        $em->flush();

        $client->loginUser($user);

        $client->request('GET', '/create');
        self::assertResponseIsSuccessful();
    }
}
