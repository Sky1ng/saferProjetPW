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

        //Si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $query = $data['field_name'];

           #$results = $em->getRepository(Bien::class)->findBy(['titre' => '%' . $query . '%']);

            /*
             * Explication général
             * Lorsque le formulaire de recherche par mot est soumi, on cherche un titre ayant le même mot que celui entré dans le formulaire
             * On aurait aussi pu mettre dans la description
             *
             * Pour le formulaire mutli-critère on compare avec les critères entré dans le formulaire et dès que des biens
             * correspondent à ces critères on les enregistrent afin de faire l'intersection plus tard. SI l'utilisateur
             * ne rentre pas de critère alors on prend l'intégralité des biens pour ensuite faire l'intersection
             */



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

            //Vérification du prix
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

            //Vérification de la catégorie (on prend que les 2 premiers chiffres du code postal
            $localisation = $data->getLocalisation();
            $resultsL = $em->getRepository(Bien::class)->createQueryBuilder('b')
                ->where('b.localisation LIKE :localisation')
                ->setParameter('localisation', $localisation . '%')
                ->getQuery()
                ->getResult();
            if($resultsP === null){
                $resultsP = $em->getRepository(Bien::class)->findAll();
            }

            //Vérification de la surface
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


            //Vérification pour la catégorie
            $categoriebis = $em->getRepository(Categorie::class)->findOneBy(['nom' => $data->getCategorie()]);
            $bienRepository = $em->getRepository(Bien::class);
            if($categoriebis != null){
                $resultC = $bienRepository->findBy(['id_categorie' => $categoriebis->getId()]);
            }else {
                $resultC = $em->getRepository(Bien::class)->findAll();
            }
            //Intersection des résultats
            $results = array_intersect($resultsP, $resultsL, $resultsS, $resultC);
           //Sinon message
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
