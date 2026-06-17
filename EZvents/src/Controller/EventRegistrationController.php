<?php

namespace App\Controller;

use App\Entity\Event;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class EventRegistrationController extends AbstractController
{
    #[Route('/event/{id}/inscription', name: 'app_event_inscription', methods: ['POST'])]
    #[IsGranted(new Expression('is_granted("ROLE_USER")'))]
    public function Inscription(Event $event, EntityManagerInterface $em): Response
    {
        if ($event->isArchived()) {
            throw $this->createNotFoundException("Cet événement n'est plus disponible.");
        }

        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        if ($event->getParticipants()->contains($user)) {
            $event->removeParticipant($user);
            $this->addFlash('success', 'Vous vous êtes désinscrit de l\'événement ❌');
        }

        else {
            if ($event->getPlacesRestantes() <= 0) {
                $this->addFlash('danger', 'Désolé, cet événement est complet ! 🚫');
                return $this->redirectToRoute('app_event', ['id' => $event->getId()]);
            }

            $event->addParticipant($user);
            $this->addFlash('success', 'Inscription validée avec succès !');
        }

        $em->flush();
        return $this->redirectToRoute('app_event', ['id' => $event->getId()]);
    }
}
