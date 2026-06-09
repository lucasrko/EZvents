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
        // 📥 1. Récupération de tous tes filtres (Déjà présents dans ton code !)
        $query = $request->query->get('q', '');
        $ville = $request->query->get('ville', '');
        $cp = $request->query->get('cp', '');
        $jeu = $request->query->get('jeu', '');
        $equipe = $request->query->get('equipe', '');
        $date = $request->query->get('date', '');

        // 🏗️ 2. Initialisation du QueryBuilder de Doctrine sur l'entité Event 'e'
        $qb = $eventRepository->createQueryBuilder('e');

        // Filtre : Nom de l'événement (Ta recherche principale d'origine)
        if (!empty($query)) {
            $qb->andWhere('e.name LIKE :query')
                ->setParameter('query', '%' . $query . '%');
        }

        // Filtre : Ville
        if (!empty($ville)) {
            $qb->andWhere('e.ville LIKE :ville')
                ->setParameter('ville', '%' . $ville . '%');
        }

        // Filtre : Code Postal
        if (!empty($cp)) {
            $qb->andWhere('e.code_postal LIKE :cp') // 🚀 Parfaitement aligné et sans "e" !
            ->setParameter('cp', $cp . '%');
        }

        // Filtre : Sélection du Jeu / Catégorie
        if (!empty($jeu)) {
            $qb->andWhere('e.categorie = :jeu')
                ->setParameter('jeu', $jeu);
        }

        // 🚀 Version corrigée avec les underscores attendus par ton entité PHP !
        if (!empty($equipe)) {
            $qb->andWhere('LOWER(e.nom_equipe_1) LIKE :equipe OR LOWER(e.nom_equipe_2) LIKE :equipe')
                ->setParameter('equipe', '%' . mb_strtolower($equipe) . '%');
        }

        // Filtre : Date (Affiche les événements à partir de cette date)
        if (!empty($date)) {
            $qb->andWhere('e.date_heure >= :date')
                ->setParameter('date', new \DateTime($date));
        }

        // 🚀 3. On exécute la requête finale avec le tri par date décroissante (comme ton code initial)
        $resultats = $qb->orderBy('e.date_heure', 'DESC')
            ->getQuery()
            ->getResult();

        // 📤 4. Renvoi des résultats au template Twig
        return $this->render('search/index.html.twig', [
            'query' => $query,
            'events' => $resultats,
        ]);
    }
}
