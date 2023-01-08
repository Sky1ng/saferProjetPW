<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Doctrine\ORM\EntityManagerInterface;


use App\Entity\Bien;
use Doctrine\Persistence\ManagerRegistry;

class HomeController extends AbstractController
{

    public function __construct(ManagerRegistry $registry)
    {
        $this->doctrine = $registry;
    }

    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        //Trois biens de façon aléatoire
        $em = $this->doctrine->getManager();
        //On récupère les biens de la bdd
        $biens = $em->getRepository(Bien::class)->findAll();
        //On mélange les biens
        shuffle($biens);
        //On récupère les 3 premiers biens
        $randomBiens = array_slice($biens, 0, 3);






        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'biens' => $randomBiens,
        ]);
    }
}
