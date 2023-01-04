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
        $biens = $em->getRepository(Bien::class)->findAll();
        shuffle($biens);
        $randomBiens = array_slice($biens, 0, 3);

        $bien = $em->getRepository(Bien::class)->findBy(['id' => $id]);
        return $this->render('bien_disponible/index.html.twig', [
            'controller_name' => 'BienDisponibleController',
            'randomBiens' => $randomBiens,
            'bien' => $bien[0]
        ]);
    }
}
