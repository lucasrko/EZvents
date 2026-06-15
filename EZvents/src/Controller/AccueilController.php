<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\EventRepository;

final class AccueilController extends AbstractController
{
    #[Route('/', name: 'app_accueil')]
    public function index(EventRepository $eventRepository): Response
    {
        $evenements = $eventRepository->findBy([], ['date_heure' => 'DESC'], 6);

        // 🚀 3. ON ENVOIE LES ÉVÉNEMENTS AU TWIG
        return $this->render('accueil/index.html.twig', [
            'events' => $evenements,
        ]);
    }
}
