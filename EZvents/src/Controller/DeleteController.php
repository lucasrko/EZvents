<?php

namespace App\Controller;

use App\Entity\Event;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
final class DeleteController extends AbstractController
{
    #[IsGranted('ROLE_USER')]
    #[Route('/event/delete/{id}', name: 'app_delete', methods: ['POST', 'GET'])]
    public function index(Event $event, EntityManagerInterface $entityManager): Response
    {
        if ($event->getOrganisateur() !== $this->getUser()) {
            throw $this->createAccessDeniedException("Impossible, vous n'êtes pas l'auteur de cet Event.");
        }
        $entityManager->remove($event);
        $entityManager->flush();

        $this->addFlash('success',"L'Event a été supprimer");
        return $this->redirectToRoute('app_profil', ['pseudo' => $this->getUser()->getPseudo()]);

    }
}
