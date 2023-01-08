<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StatController extends AbstractController
{

    #[Route("/statistiques", name:'statistiques')]
    public function index(Request $request)
    {
        $debut = $request->request->get('debut');
        $fin = $request->request->get('fin');

        $repository = $this->getDoctrine()->getRepository(Statistiques::class);

        // Création de la requête
        $query = $repository->createQueryBuilder('s')
            ->andWhere('s.date >= :debut')
            ->andWhere('s.date <= :fin')
            ->setParameter('debut', $debut)
            ->setParameter('fin', $fin)
            ->orderBy('s.date', 'ASC')
            ->getQuery();

        // Récupération des résultats
        $statistiques = $query->getResult();

        return $this->render('statistiques/index.html.twig', [
            'statistiques' => $statistiques,
        ]);
    }

}
