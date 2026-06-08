<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class EditController extends AbstractController
{
    #[IsGranted('ROLE_USER')]
    #[Route('/edit/modifier/{id}', name: 'app_edit')]
    public function index(Event $event, Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($event->getOrganisateur() !== $this->getUser()) {
            throw $this->createAccessDeniedException("Impossible, vous n'êtes pas l'auteur de cet Event");
        }
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', "Votre évènement a bien été modifié.");
            return $this->redirectToRoute('app_profil', ['pseudo' => $this->getUser()->getPseudo()]);
        }

        return $this->render('create/index.html.twig', [
            'eventForm' => $form->createView(),
            'isEdit' => true,
        ]);
    }
}
