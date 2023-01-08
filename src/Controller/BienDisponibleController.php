<?php

namespace App\Controller;

use App\Entity\Bien;
use App\Repository\BienRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Doctrine\Persistence\ManagerRegistry;

class BienDisponibleController extends AbstractController
{

    public function __construct(ManagerRegistry $registry)
    {
        $this->doctrine = $registry;
    }

    #[Route('/bien/{id}', name: 'app_bien_disponibleById')]
    public function index(String $id, EntityManagerInterface $em): Response
    {

        //Trois biens de façon aléatoire
        $em = $this->doctrine->getManager();
        //On récupère les biens de la bdd
        $biens = $em->getRepository(Bien::class)->findAll();
        //On mélange les biens
        shuffle($biens);
        //On récupère les 3 premiers biens
        $randomBiens = array_slice($biens, 0, 3);

        //On récupère le bien correspondant à l'id
        $bien = $em->getRepository(Bien::class)->findBy(['id' => $id]);
        return $this->render('bien_disponible/index.html.twig', [
            'controller_name' => 'BienDisponibleController',
            'randomBiens' => $randomBiens,
            'bien' => $bien[0]
        ]);
    }
    #[Route('/bien', name: 'app_bien_all')]
    public function indexbis(EntityManagerInterface $em): Response
    {

        $bien = $em->getRepository(Bien::class)->findAll();
        return $this->render('bien_disponible/all.html.twig', [
            'biens' => $bien
        ]);
    }
}
