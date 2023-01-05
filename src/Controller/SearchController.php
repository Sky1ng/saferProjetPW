<?php

namespace App\Controller;

use App\Entity\Bien;
use App\Form\QuickSearchType;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    #[Route('/search', name: 'app_search')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(QuickSearchType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $query = $data['field_name'];

           #$results = $em->getRepository(Bien::class)->findBy(['titre' => '%' . $query . '%']);

            $results = $em->getRepository(Bien::class)->createQueryBuilder('b')
                ->where('b.titre LIKE :query')
                ->setParameter('query', '%' . $query . '%')
                ->getQuery()
                ->getResult();



            return $this->render('search/index.html.twig', [
                'controller_name' => 'SearchController',
                'form' => $form->createView(),
                'results' => $results,
            ]);
        }
        return $this->render('search/index.html.twig', [
            'controller_name' => 'SearchController',
            'form' => $form->createView(),
        ]);
    }
}
