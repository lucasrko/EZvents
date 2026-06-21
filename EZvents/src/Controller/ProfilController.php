<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\UserRepository;
use App\Repository\EventRepository;

final class ProfilController extends AbstractController
{
    #[Route('/profil/{pseudo}', name: 'app_profil')]
    public function index(string $pseudo, UserRepository $userRepository, EventRepository $eventRepository): Response
    {
        $user = $userRepository->findOneBy(['pseudo' => $pseudo]);

        if (!$user) {
            throw $this->createNotFoundException("Cet utilisateur n'existe pas");
        }

        $myEvents = $user->getEvents()->filter(function($event) {
            return $event->isArchived() === false;
        });

        $registeredEvents = $eventRepository->createQueryBuilder('e')
            ->join('e.participants', 'p')
            ->where('p = :user')
            ->andWhere('e.isArchived = false')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();

        return $this->render('profil/index.html.twig', [
            'user' => $user,
            'events' => $myEvents,
            'registeredEvents' => $registeredEvents,
        ]);
    }
}
