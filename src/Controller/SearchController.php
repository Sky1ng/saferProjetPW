<?php

namespace App\Controller;

use App\Entity\Bien;
use App\Entity\Categorie;
use App\Form\ContactFormType;
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

        $form2 = $this->createForm(ContactFormType::class);
        $form2->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $query = $data['field_name'];

           #$results = $em->getRepository(Bien::class)->findBy(['titre' => '%' . $query . '%']);

            $results = $em->getRepository(Bien::class)->createQueryBuilder('b')
                ->where('b.titre LIKE :query')
                ->setParameter('query', '%' . $query . '%')
                ->getQuery()
                ->getResult();

            if($results === []){
                $this->addFlash('error', 'Aucun résultat trouvé');
            }

            return $this->render('search/index.html.twig', [
                'controller_name' => 'SearchController',
                'form' => $form->createView(),
                'form2' => $form2->createView(),
                'results' => $results,
            ]);
        }


        if ($form2->isSubmitted() && $form2->isValid()) {
            $data = $form2->getData();
            $prix = $data->getPrix();
            $resultsP = "";
            if ($prix === '1000') {
                $resultsP = $em->getRepository(Bien::class)->createQueryBuilder('b')
                    ->where('b.prix < :prix')
                    ->setParameter('prix', 1000)
                    ->getQuery()
                    ->getResult();
            } elseif ($prix === '1000-5000') {
                $resultsP = $em->getRepository(Bien::class)->createQueryBuilder('b')
                    ->where('b.prix > :prix')
                    ->andWhere('b.prix < :prix2')
                    ->setParameter('prix', 1000)
                    ->setParameter('prix2', 5000)
                    ->getQuery()
                    ->getResult();
            } elseif ($prix === '5000-10000') {
                $resultsP = $em->getRepository(Bien::class)->createQueryBuilder('b')
                    ->where('b.prix > :prix')
                    ->andwhere('b.prix < :prix2')
                    ->setParameter('prix', 5000)
                    ->setParameter('prix2', 10000)
                    ->getQuery()
                    ->getResult();
            } elseif ($prix === '10000+') {
                $resultsP = $em->getRepository(Bien::class)->createQueryBuilder('b')
                    ->where('b.prix > :prix')
                    ->setParameter('prix', 10000)
                    ->getQuery()
                    ->getResult();
            } else {
                $resultsP = $em->getRepository(Bien::class)->findAll();

            }

            $localisation = $data->getLocalisation();
            $resultsL = $em->getRepository(Bien::class)->createQueryBuilder('b')
                ->where('b.localisation LIKE :localisation')
                ->setParameter('localisation', $localisation . '%')
                ->getQuery()
                ->getResult();
            if($resultsP === null){
                $resultsP = $em->getRepository(Bien::class)->findAll();
            }

            $surface = $data->getSurface();
            if ($surface === '0-1') {
                $resultsS = $em->getRepository(Bien::class)->createQueryBuilder('b')
                    ->where('b.surface = :s')
                    ->setParameter('s', 1)
                    ->getQuery()
                    ->getResult();
            } elseif ($surface === '1-5') {
                $resultsS = $em->getRepository(Bien::class)->createQueryBuilder('b')
                    ->where('b.surface > :s')
                    ->andWhere('b.surface < :s2')
                    ->setParameter('s', 1)
                    ->setParameter('s2', 5)
                    ->getQuery()
                    ->getResult();
            } elseif ($surface === '5-10') {
                $resultsS = $em->getRepository(Bien::class)->createQueryBuilder('b')
                    ->where('b.surface > :s')
                    ->andWhere('b.surface < :s2')
                    ->setParameter('s', 5)
                    ->setParameter('s2', 10)
                    ->getQuery()
                    ->getResult();
            } elseif ($surface === '10-20') {
                $resultsS = $em->getRepository(Bien::class)->createQueryBuilder('b')
                    ->where('b.surface > :s')
                    ->andWhere('b.surface < :s2')
                    ->setParameter('s', 10)
                    ->setParameter('s2', 20)
                    ->getQuery()
                    ->getResult();
            } elseif ($surface === '20-50') {
                $resultsS = $em->getRepository(Bien::class)->createQueryBuilder('b')
                    ->where('b.surface > :s')
                    ->andWhere('b.surface < :s2')
                    ->setParameter('s', 20)
                    ->setParameter('s2', 50)
                    ->getQuery()
                    ->getResult();
            } elseif ($surface === '50+') {
                $resultsS = $em->getRepository(Bien::class)->createQueryBuilder('b')
                    ->where('b.surface > :s')
                    ->setParameter('s', 50)
                    ->getQuery()
                    ->getResult();

            }else{
                $resultsS = $em->getRepository(Bien::class)->findAll();
            }



            $categoriebis = $em->getRepository(Categorie::class)->findOneBy(['nom' => $data->getCategorie()]);
            $bienRepository = $em->getRepository(Bien::class);
            if($categoriebis != null){
                $resultC = $bienRepository->findBy(['id_categorie' => $categoriebis->getId()]);
            }else {
                $resultC = $em->getRepository(Bien::class)->findAll();
            }

            $results = array_intersect($resultsP, $resultsL, $resultsS, $resultC);
            if($results === []){
                $this->addFlash('error', 'Aucun résultat trouvé');
            }


            return $this->render('search/index.html.twig', [
                'controller_name' => 'SearchController',
                'form' => $form->createView(),
                'form2' => $form2->createView(),
                'results' => $results,
            ]);

        }


        return $this->render('search/index.html.twig', [
            'controller_name' => 'SearchController',
            'form' => $form->createView(),
            'form2' => $form2->createView(),
            'results' => [],

        ]);
    }


    #[Route('/search/data', name: 'app_search_data')]
    public function recherche(Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(QuickSearchType::class);
        $form->handleRequest($request);

        $form2 = $this->createForm(ContactFormType::class);
        $form2->handleRequest($request);


            $query = $request->request->get('recherche');

            $results = $em->getRepository(Bien::class)->createQueryBuilder('b')
                ->where('b.titre LIKE :query')
                ->setParameter('query', '%' . $query . '%')
                ->getQuery()
                ->getResult();

            if($results === []){
               $this->addFlash('error', 'Aucun résultat trouvé');
            }

            return $this->render('search/index.html.twig', [
                'controller_name' => 'SearchController',
                'form' => $form->createView(),
                'form2' => $form2->createView(),
                'results' => $results,
            ]);
        }


}
