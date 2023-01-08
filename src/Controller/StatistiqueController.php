<?php

namespace App\Controller;

use App\Entity\Bien;
use App\Entity\FavorisSent;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\BienRepository;

class StatistiqueController extends AbstractController
{
    
    #[Route('admin/statistique', name: 'app_statistique')]
    public function index(EntityManagerInterface $em, BienRepository $bienRepository ): Response
    {
  
        $rsm = new ResultSetMapping($em);


        $rsm->addScalarResult('id_categorie_id', 'id_categorie_id');
        $rsm->addScalarResult('nb', 'nb');
        $rsm->addScalarResult('nom', 'nom');

        $nativeQuery = $em->createNativeQuery(
            'SELECT categorie.nom,bien.id_categorie_id, count(*) as nb FROM bien JOIN favoris_sent ON FIND_IN_SET(bien.id, get_quoted_values(biens)) > 0 join categorie on categorie.id = bien.id_categorie_id where favoris_sent.date = CURRENT_DATE GROUP BY bien.id_categorie_id;',
            
        $rsm);

        $categ = $nativeQuery->getResult();
    
        $results = $em->getRepository(Bien::class)->createQueryBuilder('b')
            ->select( 'COUNT(b) as nb')
            ->groupBy('b.id_categorie')
            ->getQuery()
            ->getResult();

        $rsm2 = new ResultSetMapping($em);
        
        $rsm2->addScalarResult('titre', 'titre');
        $rsm2->addScalarResult('localisation', 'localisation');
        $rsm2->addScalarResult('id', 'id');
        $rsm2->addScalarResult('count', 'count');
    
        $nativeQuery2 = $em->createNativeQuery(
                'SELECT bien.titre, bien.localisation,bien.id, count(*) as count FROM bien JOIN favoris_sent ON FIND_IN_SET(bien.id, get_quoted_values(biens)) > 0 GROUP BY bien.titre, bien.localisation HAVING count = (SELECT MAX(count) FROM (SELECT bien.titre, bien.localisation, count(*) as count FROM bien JOIN favoris_sent ON FIND_IN_SET(bien.id, get_quoted_values(biens)) > 0 GROUP BY bien.titre, bien.localisation) as counts WHERE bien.localisation = counts.localisation);',
                
            $rsm2);
    
        $depart = $nativeQuery2->getResult();

        return $this->render('statistique/index.html.twig', [
            'controller_name' => 'StatistiqueController',
            'results' => $results,
            'depart' => $depart,
            'categ' => $categ,
            'biens' => $bienRepository->findAll()
        ]);
    }


}
