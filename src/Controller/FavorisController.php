<?php

namespace App\Controller;

use App\Entity\Bien;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


#[Route('/favoris')]
class FavorisController extends AbstractController
{


    #[Route('/', name: 'app_favoris_index', methods: ['GET'])]
    public function index(SessionInterface $session,EntityManagerInterface $em): Response
    {

        $biens = $em->getRepository(Bien::class)->findBy(['id' => $session->get('favoris')]);


        return $this->render('favoris/index.html.twig', [
            'biens' => $biens
        ]);
    }

    #[Route('/add/{id}', name: 'app_admin_new', methods: ['GET', 'POST'])]
    public function addFavoris(string $id,SessionInterface $session): Response
    {
        $tab = $session->get('favoris');
        $tab[] = $id;
        $tableau = array_unique($tab);
        $session->set('favoris', $tableau);

        return $this->redirectToRoute('app_favoris_index', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/remove', name: 'app_admin_remove', methods: ['GET', 'POST'])]
    public function removeFavoris(SessionInterface $session): Response
    {
        $session->clear();

        return $this->redirectToRoute('app_favoris_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/remove/{id}', name: 'app_admin_removeById', methods: ['GET', 'POST'])]
    public function removeFavorisById(string $id,SessionInterface $session): Response
    {
        $tab = $session->get('favoris');

        $indice = array_search($id, $tab);

        if ($indice !== false) {
            unset($tab[$indice]);
        }
        $session->set('favoris', $tab);
        return $this->redirectToRoute('app_favoris_index', [], Response::HTTP_SEE_OTHER);
    }

}
