<?php

namespace App\Controller;

use App\Entity\Bien;
use App\Repository\BienRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BienDisponibleController extends AbstractController
{
    #[Route('/bien', name: 'app_bien_disponible')]
    public function index(BienRepository $bienRepository): Response
    {

        return $this->render('bien_disponible/index.html.twig', [
            'controller_name' => 'BienDisponibleController',
            'biens' => $bienRepository->findAll(),
        ]);
    }
    #[Route('/bien/{id}', name: 'app_bien_disponibleById')]
    public function getBien(String $id,EntityManagerInterface $em): Response
    {

        $bien = $em->getRepository(Bien::class)->findBy(['id' => $id]);
        return $this->render('bien_disponible/bienById.html.twig', [
            'controller_name' => 'BienDisponibleController',
            'bien' => $bien[0]
        ]);
    }


}
