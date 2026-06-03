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

final class CreateController extends AbstractController
{
    #[IsGranted('ROLE_USER')]
    #[Route('/create', name: 'app_create')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $event = new Event();

        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $event->setOrganisateur($this->getUser());

            $entityManager->persist($event);
            $entityManager->flush();

            $this->addFlash('success', "Votre événement a bien été créé !");
            return $this->redirectToRoute('app_accueil');
        }

        if ($form->isSubmitted()) {
            dd($form->getErrors(deep: true, flatten: true)->__toString());
        }

        return $this->render('create/index.html.twig', [
            'eventForm' => $form->createView(),
        ]);
    }
}
