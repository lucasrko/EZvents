<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Event;

final class EventController extends AbstractController
{
    #[Route('/event/{id}', name: 'app_event')]
    public function show(Event $event): Response
    {
        return $this->render('event/index.html.twig', [
            'event' => $event,
        ]);
    }
}
