<?php

namespace App\Tests;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class LoginControllerTest extends WebTestCase
{
    private KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $container = static::getContainer();
        $em = $container->get('doctrine.orm.entity_manager');

        // 1. NOUVEAU : On supprime d'abord tous les événements existants !
        $eventRepository = $em->getRepository(\App\Entity\Event::class);
        foreach ($eventRepository->findAll() as $event) {
            $em->remove($event);
        }

        // 2. Ensuite, on peut supprimer les utilisateurs sans bloquer la base de données
        $userRepository = $em->getRepository(User::class);
        foreach ($userRepository->findAll() as $user) {
            $em->remove($user);
        }

        $em->flush();

        // Create a User fixture
        /** @var UserPasswordHasherInterface $passwordHasher */
        $passwordHasher = $container->get('security.user_password_hasher');

        $user = new \App\Entity\User();
        $user->setEmail('test@ezvents.fr');

        // C'est ici qu'on hache le mot de passe pour que la connexion fonctionne
        $user->setPassword($passwordHasher->hashPassword($user, 'password'));

        // Les champs qui correspondent à votre base de données :
        $user->setName('Dupont');
        $user->setFirstname('Jean');
        $user->setPseudo('NeoPlayer_X');
        $user->setTelephone('0600000000');

        $em->persist($user);
        $em->flush();
    }

    public function testLogin(): void
    {
        // 1. Denied - Can't login with invalid email address.
        $this->client->request('GET', '/login');
        self::assertResponseIsSuccessful();

        $this->client->submitForm('Se connecter', [
            '_username' => 'doesNotExist@example.com', // <-- _username
            '_password' => 'password',                 // <-- _password
        ]);

        self::assertResponseRedirects('/login');
        $this->client->followRedirect();

        self::assertSelectorTextContains('.alert-danger', 'Invalid credentials.');

        // 2. Denied - Can't login with invalid password.
        $this->client->request('GET', '/login');
        self::assertResponseIsSuccessful();

        $this->client->submitForm('Se connecter', [
            '_username' => 'test@ezvents.fr', // <-- _username
            '_password' => 'bad-password',    // <-- _password
        ]);

        self::assertResponseRedirects('/login');
        $this->client->followRedirect();

        self::assertSelectorTextContains('.alert-danger', 'Invalid credentials.');

        // 3. Success - Login with valid credentials is allowed.
        $this->client->submitForm('Se connecter', [
            '_username' => 'test@ezvents.fr', // <-- _username
            '_password' => 'password',        // <-- _password
        ]);

        self::assertResponseRedirects('/');
        $this->client->followRedirect();

        self::assertSelectorNotExists('.alert-danger');
    }
}
