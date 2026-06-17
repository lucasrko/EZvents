<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\EventRepository;
use Symfony\Component\HttpFoundation\Request;

final class SearchController extends AbstractController
{
    #[Route('/search', name: 'app_search')]
    public function index(Request $request, EventRepository $eventRepository): Response
    {

        $query = $request->query->get('q', '');
        $ville = $request->query->get('ville', '');
        $cp = $request->query->get('cp', '');
        $jeu = $request->query->get('jeu', '');
        $equipe = $request->query->get('equipe', '');
        $date = $request->query->get('date', '');

        $qb = $eventRepository->createQueryBuilder('e')
            ->andWhere('e.isArchived = :archived')
            ->setParameter('archived', false);

        if (!empty($query)) {
            $qb->andWhere('e.name LIKE :query')
                ->setParameter('query', '%' . $query . '%');
        }

        if (!empty($ville)) {
            $qb->andWhere('e.ville LIKE :ville')
                ->setParameter('ville', '%' . $ville . '%');
        }

        if (!empty($cp)) {
            $qb->andWhere('e.code_postal LIKE :cp') // 🚀 Parfaitement aligné et sans "e" !
            ->setParameter('cp', $cp . '%');
        }

        if (!empty($jeu)) {
            $qb->andWhere('e.categorie = :jeu')
                ->setParameter('jeu', $jeu);
        }

        if (!empty($equipe)) {
            $qb->andWhere('LOWER(e.nom_equipe_1) LIKE :equipe OR LOWER(e.nom_equipe_2) LIKE :equipe')
                ->setParameter('equipe', '%' . mb_strtolower($equipe) . '%');
        }

        if (!empty($date)) {
            $qb->andWhere('e.date_heure >= :date')
                ->setParameter('date', new \DateTime($date));
        }

        $resultats = $qb->orderBy('e.date_heure', 'DESC')
            ->getQuery()
            ->getResult();

        return $this->render('search/index.html.twig', [
            'query' => $query,
            'events' => $resultats,
        ]);
    }
}
