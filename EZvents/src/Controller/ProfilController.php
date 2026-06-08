<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\UserRepository;

final class ProfilController extends AbstractController
{
    #[Route('/profil/{pseudo}', name: 'app_profil')]
    public function index(string $pseudo,UserRepository $userRepository): Response
    {
        $user = $userRepository->findOneBy(['pseudo' => $pseudo]);

        if (!$user) {
            throw $this->createNotFoundException("Cet utilisateur n'existe pas");
        }
        $myEvents = $user->getEvents();
        return $this->render('profil/index.html.twig', [
            'user' => $user,
            'events' => $myEvents,
        ]);
    }
}
