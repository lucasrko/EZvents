<?php

namespace App\Tests;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\User;

class RegistrationControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private UserRepository $userRepository;

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
        $this->userRepository = $em->getRepository(User::class);
        foreach ($this->userRepository->findAll() as $user) {
            $em->remove($user);
        }

        $em->flush();
    }

    public function testRegister(): void
    {
        // Register a new user
        $this->client->request('GET', '/register');
        self::assertResponseIsSuccessful();
        self::assertPageTitleContains('Inscription');

        $this->client->submitForm('Créer mon compte', [
            'registration_form[email]' => 'test@example.com',
            'registration_form[plainPassword]' => 'password',
            'registration_form[agreeTerms]' => true,
            'registration_form[name]' => 'Dupont',
            'registration_form[firstname]' => 'Jean',
            'registration_form[pseudo]' => 'NeoPlayer_X',
            'registration_form[telephone]' => '0600000000',
        ]);

        // Ensure the response redirects after submitting the form, the user exists, and is not verified
        self::assertResponseRedirects('/'); 
        self::assertCount(1, $this->userRepository->findAll());
    }
}
