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
        $query = $request->query->get('q','');

        $resultats = [];
        if(!empty($query)){
            $resultats = $eventRepository->createQueryBuilder('e')
                ->where('e.name LIKE :query')
                ->setParameter('query','%'.$query.'%')
                ->orderBy('e.date_heure','DESC')
                ->getQuery()
                ->getResult();
        }
        return $this->render('search/index.html.twig', [
            'query' => $query,
            'events' => $resultats,
        ]);
    }
}
