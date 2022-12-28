<?php

namespace App\Controller;

use App\Entity\Bien;
use App\Entity\Categorie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnnonceController extends AbstractController
{
    #[Route('/annonce/{categorie}', name: 'app_annonce')]
    public function index(string $categorie, EntityManagerInterface $entityManager): Response
    {
        $categoriebis = $entityManager->getRepository(Categorie::class)->findOneBy(['nom' => $categorie]);

        #check si la catégorie exsite, si elle existe on renvoie un tableau avec tous les biens correspondant a ce type de bien
        #SELECT * FROM `bien` WHERE categorie_id = (SELECT id FROM `categorie` where nom = "Terrain Agricole");
        $bienRepository = $entityManager->getRepository(Bien::class);
        $bien = $bienRepository->findBy(['id_categorie' => $categoriebis->getId()]);



        return $this->render('annonce/index.html.twig', [
            'controller_name' => 'AnnonceController','biens' => $bien
        ]);
    }
}
