<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StatistiqueController extends AbstractController
{
    #[Route('admin/statistique', name: 'app_statistique')]
    public function index(EntityManagerInterface $em ): Response
    {

        $query = $em->createQuery(

        );

        $result = $query->getResult();

        return $this->render('statistique/index.html.twig', [
            'controller_name' => 'StatistiqueController',
        ]);
    }
}
